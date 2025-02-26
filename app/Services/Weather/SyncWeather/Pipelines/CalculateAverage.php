<?php

namespace App\Services\Weather\SyncWeather\Pipelines;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use Closure;

class CalculateAverage
{
    public function handle(WeatherParamDTO $DTO, Closure $next)
    {
        $data = $DTO->getProviderData();

        $uvIndex = 0;
        $precipitation = 0;
        $count = 0;
        foreach ($data as $item) {
            if (empty($item) === true) {
                continue;
            }

            $uvIndex += $item['uv_index'];
            $precipitation += $item['precipitation'];
            $count++;
        }

        $DTO->setResult([
            'uv_index' => $uvIndex / ($count ?: 1),
            'precipitation' => $precipitation / ($count ?: 1),
        ]);

        return $next($DTO);
    }
}
