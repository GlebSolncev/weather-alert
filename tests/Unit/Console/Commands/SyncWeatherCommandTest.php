<?php

namespace Tests\Unit\Console\Commands;

use App\Services\Weather\SyncWeatherService;
use Tests\TestCase;

class SyncWeatherCommandTest extends TestCase
{
    public function test_command_calls_service(): void
    {
        $service = $this->createMock(SyncWeatherService::class);
        $service->expects($this->once())->method('dispatchSync');

        $this->app->instance(SyncWeatherService::class, $service);

        $this->artisan('weather:sync')
            ->assertSuccessful();
    }
}
