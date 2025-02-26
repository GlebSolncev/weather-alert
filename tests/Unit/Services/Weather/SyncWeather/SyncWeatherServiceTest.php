<?php

namespace Tests\Unit\Services\Weather\SyncWeather;

use App\Jobs\SyncWeatherJob;
use App\Models\Location;
use App\Services\Weather\SyncWeatherService;
use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\Unit\Services\TestCase;

class SyncWeatherServiceTest extends TestCase
{
    private SyncWeatherService $service;

    private $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = $this->createMock(Pipeline::class);
        $this->service = new SyncWeatherService($this->pipeline);
        App::instance(Log::class, Log::class);
    }

    public function test_dispatch_sync_successfully_dispatches_jobs(): void
    {
        Queue::fake();
        Log::shouldReceive('info')->once();

        $locations = Location::factory()->count(2)->create();

        $this->service->dispatchSync();

        Queue::assertPushed(SyncWeatherJob::class, 2);
        $locations->each(function ($location) {
            Queue::assertPushed(function (SyncWeatherJob $job) {
                return property_exists($job, 'locationData');
            });
        });
    }

    public function test_dispatch_sync_handles_empty_locations(): void
    {
        Queue::fake();
        Log::shouldReceive('info')->once();

        $this->service->dispatchSync();

        Queue::assertNotPushed(SyncWeatherJob::class);
    }

    public function test_dispatch_sync_handles_exception(): void
    {
        Queue::fake();
        Log::shouldReceive('error')->once();

        Location::factory()->create()->delete();

        $this->expectException(Exception::class);

        $this->service->dispatchSync();
    }
}
