<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SyncWeatherJob;
use App\Services\Weather\SyncWeather\WeatherPipelineService;
use Tests\TestCase;

class SyncWeatherJobTest extends TestCase
{
    public function test_job_handles_sync(): void
    {
        $service = $this->createMock(WeatherPipelineService::class);
        $service->expects($this->once())->method('process');

        $job = new SyncWeatherJob([
            'id' => 1,
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $job->handle($service);
    }
}
