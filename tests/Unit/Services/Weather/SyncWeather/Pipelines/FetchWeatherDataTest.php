<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Pipelines;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Pipelines\FetchWeatherData;
use App\Services\Weather\SyncWeather\Utils\OpenMeteoProvider;
use App\Services\Weather\SyncWeather\Utils\WeatherApiProvider;
use Mockery;
use Tests\Unit\Services\TestCase;

class FetchWeatherDataTest extends TestCase
{
    private FetchWeatherData $pipeline;

    private OpenMeteoProvider $openMeteoProvider;

    private WeatherApiProvider $weatherApiProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->openMeteoProvider = Mockery::spy(OpenMeteoProvider::class);
        $this->weatherApiProvider = Mockery::mock(WeatherApiProvider::class);

        app()->instance(OpenMeteoProvider::class, $this->openMeteoProvider);
        app()->instance(WeatherApiProvider::class, $this->weatherApiProvider);

        $this->pipeline = new FetchWeatherData;
    }

    public function test_fetches_weather_data_from_providers(): void
    {
        $weatherData = $this->createWeatherParamByUtils();

        $this->openMeteoProvider->shouldReceive('getWeather')
            ->once()
            ->andReturn([
                'uv_index' => 0,
                'precipitation' => 0,
            ]);

        $this->weatherApiProvider->shouldReceive('getWeather')
            ->once()
            ->andReturn([
                'uv_index' => 0,
                'precipitation' => 0,
            ]);

        $dto = new WeatherParamDTO([
            'id' => 1,
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $this->assertEquals($weatherData, $dto->getProviderData());
    }

    public function test_handle_fetches_weather_data(): void
    {
        $openMeteo = $this->createMock(OpenMeteoProvider::class);
        $weatherApi = $this->createMock(WeatherApiProvider::class);

        $openMeteo->method('getWeather')->willReturn(['uv_index' => 1]);
        $weatherApi->method('getWeather')->willReturn(['precipitation' => 2]);

        $this->app->instance(OpenMeteoProvider::class, $openMeteo);
        $this->app->instance(WeatherApiProvider::class, $weatherApi);

        $dto = new WeatherParamDTO([
            'latitude' => 1,
            'longitude' => 1,
        ]);

        $pipeline = new FetchWeatherData;
        $result = $pipeline->handle($dto, fn ($dto) => $dto);

        $this->assertNotEmpty($result->getProviderData());
    }
}
