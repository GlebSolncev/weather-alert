<?php

namespace App\Services\Weather\SyncWeather\Utils;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;

interface WeatherProviderInterface
{
    public function getWeather(WeatherParamDTO $DTO): array;
}
