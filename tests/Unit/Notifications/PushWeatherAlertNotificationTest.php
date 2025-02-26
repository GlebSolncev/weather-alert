<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Notifications\PushWeatherAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PushWeatherAlertNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_creates_webpush_notification(): void
    {
        $location = 'Odessa, Ukraine';
        $weatherData = [
            'uv' => 5.5,
            'precipitation' => 10.2,
        ];

        $notification = new PushWeatherAlertNotification($location, $weatherData);
        $user = User::factory()->create();

        $webPush = $notification->toWebPush($user);

        $this->assertEquals([
            'title' => sprintf('Weather alert for %s', $location),
            'body' => sprintf('UV: %s, Precipitation: %s', $weatherData['uv'], $weatherData['precipitation']),
        ], $webPush->toArray());
    }

    public function test_creates_array_representation(): void
    {
        $location = 'Odessa, Ukraine';
        $weatherData = [
            'uv' => 5.5,
            'precipitation' => 10.2,
        ];

        $notification = new PushWeatherAlertNotification($location, $weatherData);
        $user = User::factory()->create();

        $this->assertEquals([
            'title' => sprintf('Weather alert for %s', $location),
            'body' => sprintf('UV: %s, Precipitation: %s', $weatherData['uv'], $weatherData['precipitation']),
        ], $notification->toWebPush($user)->toArray());
    }
}
