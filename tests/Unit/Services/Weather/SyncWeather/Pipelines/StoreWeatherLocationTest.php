<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Pipelines;

use App\Models\Location;
use App\Models\WeatherCondition;
use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Pipelines\StoreWeatherLocation;
use Tests\Unit\Services\TestCase;

class StoreWeatherLocationTest extends TestCase
{
    private StoreWeatherLocation $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new StoreWeatherLocation;
    }

    public function test_stores_weather_data(): void
    {
        $location = Location::factory()->create();

        $dto = new WeatherParamDTO([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        $weatherData = $this->createWeatherData();
        $dto->setResult($weatherData);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $this->assertDatabaseHas('weather_conditions', [
            'location_id' => $location->id,
            'uv_index' => $weatherData['uv_index'],
            'precipitation' => $weatherData['precipitation'],
        ]);
    }

    public function test_handles_missing_result_data(): void
    {
        $location = Location::factory()->create();

        $dto = new WeatherParamDTO([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $this->assertDatabaseMissing('weather_conditions', [
            'location_id' => $location->id,
        ]);
    }

    public function test_updates_existing_weather_condition(): void
    {
        $location = Location::factory()->create();
        $oldWeather = WeatherCondition::factory()->create([
            'location_id' => $location->id,
            'uv_index' => 1.0,
            'precipitation' => 2.0,
        ]);

        $dto = new WeatherParamDTO([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        $newWeatherData = $this->createWeatherData();
        $dto->setResult($newWeatherData);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $this->assertDatabaseHas('weather_conditions', [
            'location_id' => $location->id,
            'uv_index' => $newWeatherData['uv_index'],
            'precipitation' => $newWeatherData['precipitation'],
        ]);
    }
}
