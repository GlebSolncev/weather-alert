<?php

namespace App\Services\Weather\SyncWeather\Pipelines;

use App\Models\Location;
use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use Closure;
use Exception;
use Illuminate\Support\Facades\Log;

class StoreWeatherLocation
{
    public function handle(WeatherParamDTO $DTO, Closure $next)
    {
        try {
            $location = Location::find($DTO->getLocationId());
            $location->weatherConditions()->create($DTO->getResult());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return null;
        }

        return $next($DTO);
    }
}
