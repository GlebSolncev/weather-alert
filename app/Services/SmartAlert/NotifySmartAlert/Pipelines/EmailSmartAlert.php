<?php

namespace App\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\User;
use App\Notifications\PushWeatherAlertNotification;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailSmartAlert
{
    public function handle(NotifySmartAlertDTO $DTO, Closure $next)
    {
        $collection = $DTO->getUsers()->where('push_notification', true);

        $collection->map(function (User $user) use ($DTO) {
            try {
                $user->notify(new PushWeatherAlertNotification(
                    $DTO->getLocation()->getFullAddressAttribute(),
                    [
                        'uv' => $DTO->getCurrentUVIndex(),
                        'precipitation' => $DTO->getCurrentPrecipitation(),
                    ]
                ));
            } catch (Throwable $exception) {
                Log::error('Email has problem for sent '.$user->email.' - '.$exception->getMessage());

                return;
            }
        });

        return $next($DTO);
    }
}
