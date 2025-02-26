<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Utils;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Utils\WeatherApiProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\Unit\Services\TestCase;

class WeatherApiProviderTest extends TestCase
{
    private WeatherApiProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('weather.weather_api', [
            'api_key' => 'test_key',
            'base_url' => 'http://test.com',
        ]);

        $this->provider = new WeatherApiProvider;
    }

    public function test_get_weather_returns_formatted_data(): void
    {
        Http::fake([
            '*' => Http::response([
                'current' => [
                    'uv' => 5.7,
                    'precip_mm' => 0.5,
                ],
            ]),
        ]);

        $dto = new WeatherParamDTO([
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $result = $this->provider->getWeather($dto);

        $this->assertEquals([
            'uv_index' => 5.7,
            'precipitation' => 0.5,
        ], $result);
    }

    public function test_get_weather_handles_failed_response(): void
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $dto = new WeatherParamDTO([
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $result = $this->provider->getWeather($dto);

        $this->assertEquals([], $result);
    }
}
