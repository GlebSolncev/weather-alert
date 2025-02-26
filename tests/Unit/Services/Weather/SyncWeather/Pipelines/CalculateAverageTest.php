<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Pipelines;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Pipelines\CalculateAverage;
use Tests\Unit\Services\TestCase;

class CalculateAverageTest extends TestCase
{
    private CalculateAverage $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new CalculateAverage;
    }

    public function test_calculates_average_from_providers(): void
    {
        $dto = new WeatherParamDTO([
            'id' => 1,
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $weatherData1 = ['uv_index' => 5.0, 'precipitation' => 10.0];
        $weatherData2 = ['uv_index' => 6.0, 'precipitation' => 12.0];

        $dto->setProviderData('open_meteo', $weatherData1);
        $dto->setProviderData('weather_api', $weatherData2);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $result = $dto->getResult();
        $this->assertEquals(5.5, $result['uv_index']);
        $this->assertEquals(11.0, $result['precipitation']);
    }

    public function test_handles_missing_provider_data(): void
    {
        $dto = new WeatherParamDTO([
            'id' => 1,
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $result = $dto->getResult();
        $this->assertEquals(0, $result['uv_index']);
        $this->assertEquals(0, $result['precipitation']);
    }
}
