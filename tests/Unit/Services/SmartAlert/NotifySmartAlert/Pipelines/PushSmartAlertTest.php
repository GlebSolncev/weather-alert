<?php

namespace Tests\Unit\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\Location;
use App\Models\User;
use App\Notifications\EmailWeatherAlertNotification;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\PushSmartAlert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\Unit\Services\TestCase;

class PushSmartAlertTest extends TestCase
{
    private PushSmartAlert $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new PushSmartAlert;
        Notification::fake();
    }

    public function test_sends_notifications_to_users_with_email_enabled(): void
    {
        $location = Location::factory()->create();
        $users = Collection::make([
            User::factory()->create(['email_notification' => true]),
            User::factory()->create(['email_notification' => false]),
            User::factory()->create(['email_notification' => true]),
        ]);

        $dto = new NotifySmartAlertDTO([
            'weatherData' => $this->createWeatherData(),
            'locationId' => 1,
            'userIds' => $users->pluck('id')->toArray(),
        ]);
        $dto->setLocation($location);
        $dto->setUsers($users);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        Notification::assertSentTo(
            $users->where('email_notification', true),
            EmailWeatherAlertNotification::class
        );

        Notification::assertNotSentTo(
            $users->where('email_notification', false),
            EmailWeatherAlertNotification::class
        );
    }
}
