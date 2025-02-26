<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Notifications\EmailWeatherAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailWeatherAlertNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_creates_mail_notification(): void
    {
        $location = 'Odessa, Ukraine';
        $weatherData = [
            'uv' => 5.5,
            'precipitation' => 10.2,
        ];

        $notification = new EmailWeatherAlertNotification($location, $weatherData);
        $user = User::factory()->create();

        $mail = $notification->toMail($user);

        $this->assertEquals("Weather Alert for {$location}", $mail->subject);
        $this->assertEquals('emails.weather-alert', $mail->markdown);
    }

    public function test_creates_array_representation(): void
    {
        $location = 'Odessa, Ukraine';
        $weatherData = [
            'uv' => 5.5,
            'precipitation' => 10.2,
        ];

        $notification = new EmailWeatherAlertNotification($location, $weatherData);
        $user = User::factory()->create();

        $array = $notification->toArray($user);

        $this->assertEquals([
            'location' => $location,
            'weatherData' => $weatherData,
        ], $array);
    }
}
