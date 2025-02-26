<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Pipelines;

use App\Jobs\SmartAlertJob;
use App\Models\Location;
use App\Models\User;
use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Pipelines\DispatchSmartAlert;
use Illuminate\Support\Facades\Queue;
use Tests\Unit\Services\TestCase;

class DispatchSmartAlertTest extends TestCase
{
    private DispatchSmartAlert $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new DispatchSmartAlert;
        Queue::fake();
    }

    public function test_dispatches_smart_alert_for_exceeded_thresholds(): void
    {
        $location = Location::factory()->create();
        $users = User::factory()->count(2)->create();

        // Создаем связи с превышенными порогами
        $location->users()->attach($users->pluck('id')->toArray(), [
            'max_uv' => 5.0,
            'max_precipitation' => 10.0,
        ]);

        $dto = new WeatherParamDTO([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        // Устанавливаем значения выше порогов
        $dto->setResult([
            'uv_index' => 6.0,
            'precipitation' => 11.0,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        Queue::assertPushed(SmartAlertJob::class, function ($job) {
            return property_exists($job, 'param');
        });
    }

    public function test_does_not_dispatch_for_normal_thresholds(): void
    {
        $location = Location::factory()->create();
        $users = User::factory()->count(2)->create();

        // Создаем связи с высокими порогами
        $location->users()->attach($users->pluck('id')->toArray(), [
            'max_uv' => 10.0,
            'max_precipitation' => 20.0,
        ]);

        $dto = new WeatherParamDTO([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        // Устанавливаем значения ниже порогов
        $dto->setResult([
            'uv_index' => 5.0,
            'precipitation' => 10.0,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        Queue::assertNotPushed(SmartAlertJob::class);
    }
}
