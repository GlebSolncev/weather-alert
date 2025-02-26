<?php

namespace App\Services\Location\UpdateLocation\Pipelines;

use App\Models\Location;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;

class StoreLocation
{
    public function handle(UpdateLocationDTO $DTO, \Closure $next)
    {
        $syncLocationIds = [];
        $user = $DTO->getUser();
        foreach ($DTO->getLocations() as $locationData) {
            $location = Location::query()->where([
                ['country', '=', $locationData['country']],
                ['city', '=', $locationData['city']],
            ])->first();

            if (! $location) {
                $location = $user->locations()->create($locationData);
            }

            $syncLocationIds[$location->id] = [
                'max_uv' => $locationData['max_uv'],
                'max_precipitation' => $locationData['max_precipitation'],
            ];
        }

        if ($syncLocationIds !== []) {
            $user->locations()->sync($syncLocationIds);
        }

        return $next($DTO);
    }
}
