<?php

namespace App\Services\Weather\SyncWeather\Utils;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoProvider implements WeatherProviderInterface
{
    private Client $client;

    private string $url;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = config('weather.open_meteo.base_url');
    }

    public function getWeather(WeatherParamDTO $DTO): array
    {
        $response = Http::baseUrl($this->url)
            ->get('v1/forecast', [
                'latitude' => $DTO->getLatitude(),
                'longitude' => $DTO->getLongitude(),
                'current' => 'precipitation,uv_index',
            ]);

        if ($response->failed() && $response->getStatusCode() !== Response::HTTP_OK) {
            Log::warning('Open-Meteo API failed: '.$response->body());

            return [];
        }

        $data = $response->json();

        return [
            'uv_index' => $data['current']['uv_index'] ?? 0,
            'precipitation' => $data['current']['precipitation'] ?? 0,
        ];
    }
}
