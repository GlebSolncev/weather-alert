<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushWeatherAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $location,
        private readonly array $weatherData

    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toWebPush($notifiable): WebPushMessage
    {
        return (new WebPushMessage)
            ->title(sprintf('Weather alert for %s', $this->location))
            ->body(sprintf('UV: %s, Precipitation: %s', $this->weatherData['uv'], $this->weatherData['precipitation']))
            ->options(['TTL' => 1000]);
    }
}
