<?php

namespace App\Services\Weather\SyncWeather\Utils;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherApiProvider implements WeatherProviderInterface
{
    private string $url;

    private string $apiKey;

    public function __construct()
    {
        $config = config('weather.weather_api');
        $this->apiKey = $config['api_key'];
        $this->url = $config['base_url'];
    }

    public function getWeather(WeatherParamDTO $DTO): array
    {
        $response = Http::baseUrl($this->url)
            ->get('v1/current.json', [
                'key' => $this->apiKey,
                'q' => sprintf('%s,%s', $DTO->getLatitude(), $DTO->getLongitude()),
            ]);

        if ($response->failed()) {
            Log::warning('WeatherAPI failed: '.$response->body());

            return [];
        }

        $data = $response->json();

        return [
            'uv_index' => $data['current']['uv'] ?? 0,
            'precipitation' => $data['current']['precip_mm'] ?? 0,
        ];
    }
}
