<?php

namespace App\Services\Location\UpdateLocation\Pipelines;

use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\Utils\OpenStreetMapService;

class ValidationLocation
{
    public function __construct(
        private readonly OpenStreetMapService $mapService,
    ) {}

    public function handle(UpdateLocationDTO $DTO, \Closure $next)
    {
        $locations = $DTO->getLocations();
        foreach ($locations as $inx => $location) {
            $data = $this->mapService->getLocationInfo($location['country'], $location['city']);

            $locations[$inx]['city'] = $data['city'];
            $locations[$inx]['country'] = $data['country'];
            $locations[$inx]['latitude'] = $data['latitude'];
            $locations[$inx]['longitude'] = $data['longitude'];
        }
        $DTO->setLocations($locations);

        return $next($DTO);
    }
}
