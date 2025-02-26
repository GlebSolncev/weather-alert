<?php

namespace App\Services\Weather\SyncWeather\Enumes;

use App\Services\Weather\SyncWeather\Utils\OpenMeteoProvider;
use App\Services\Weather\SyncWeather\Utils\WeatherApiProvider;

enum WeatherUtils: string
{
    case OPEN_METEO = 'open_meteo';

    case WEATHER_API = 'weather_api';

    public function className(): string
    {
        return match ($this) {
            self::OPEN_METEO => OpenMeteoProvider::class,
            self::WEATHER_API => WeatherApiProvider::class,
        };
    }
}
