<?php

namespace Tests\Unit\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\Location;
use App\Models\User;
use App\Notifications\PushWeatherAlertNotification;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\EmailSmartAlert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\Unit\Services\TestCase;

class EmailSmartAlertTest extends TestCase
{
    private EmailSmartAlert $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new EmailSmartAlert;
        Notification::fake();
    }

    public function test_sends_notifications_to_users_with_push_enabled(): void
    {
        $location = Location::factory()->create();
        $users = Collection::make([
            User::factory()->create(['push_notification' => true]),
            User::factory()->create(['push_notification' => false]),
            User::factory()->create(['push_notification' => true]),
        ]);

        $dto = new NotifySmartAlertDTO([
            'weatherData' => $this->createWeatherData(),
            'locationId' => $location->id,
            'userIds' => $users->pluck('id')->toArray(),
        ]);
        $dto->setLocation($location);
        $dto->setUsers($users);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        Notification::assertSentTo(
            $users->where('push_notification', true),
            PushWeatherAlertNotification::class
        );

        Notification::assertNotSentTo(
            $users->where('push_notification', false),
            PushWeatherAlertNotification::class
        );
    }

    public function test_handles_empty_users_collection(): void
    {
        $dto = new NotifySmartAlertDTO([
            'weatherData' => $this->createWeatherData(),
            'locationId' => 1,
            'userIds' => [],
        ]);
        $dto->setUsers(new Collection);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        Notification::assertNothingSent();
    }
}
