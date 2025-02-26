<?php

namespace App\Services\Location\UpdateLocation\Pipelines;

use App\Jobs\SyncWeatherJob;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;

class DispatchWeatherCondition
{
    public function handle(UpdateLocationDTO $DTO, \Closure $next)
    {
        $DTO->getUser()->locations->map(function ($location) {
            if ($location->weatherConditions()->exists() === false) {
                SyncWeatherJob::dispatch($location->toArray());
            }
        });

        return $next($DTO);
    }
}
