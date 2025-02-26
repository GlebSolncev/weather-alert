<?php

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Используем SQLite в памяти для тестов
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Добавляем тестовую конфигурацию для погодных сервисов
        Config::set('weather.weather_api', [
            'api_key' => 'test_key',
            'base_url' => 'https://api.weatherapi.com',
        ]);

        Config::set('weather.open_meteo', [
            'base_url' => 'https://api.open-meteo.com',
        ]);

        $this->withoutExceptionHandling();
    }

    protected function createLocationData(array $override = []): array
    {
        return array_merge([
            'country' => 'Ukraine',
            'city' => 'Odessa',
            'latitude' => 46.4825,
            'longitude' => 30.7233,
            'max_uv' => 5,
            'max_precipitation' => 10,
        ], $override);
    }

    protected function createWeatherParamByUtils(array $override = []): array
    {
        return array_merge([
            'open_meteo' => [
                'uv_index' => 0.0,
                'precipitation' => 0.0,
            ],
            'weather_api' => [
                'uv_index' => 0.0,
                'precipitation' => 0.0,
            ],
        ], $override);
    }

    protected function createWeatherData(array $override = []): array
    {
        return array_merge([
            'uv_index' => 0.0,
            'precipitation' => 0.0,
        ], $override);
    }
}
