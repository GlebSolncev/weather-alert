<?php

namespace Tests\Unit\Services\Weather\SyncWeather;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\WeatherPipelineService;
use Illuminate\Pipeline\Pipeline;
use Tests\Unit\Services\TestCase;

class WeatherPipelineServiceTest extends TestCase
{
    private WeatherPipelineService $service;

    private Pipeline $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = $this->createMock(Pipeline::class);
        $this->service = new WeatherPipelineService($this->pipeline);
    }

    public function test_process_executes_pipeline(): void
    {
        $dto = new WeatherParamDTO([
            'id' => 1,
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $this->pipeline->expects($this->once())
            ->method('send')
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('through')
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('thenReturn');

        $this->service->process($dto);
    }
}
