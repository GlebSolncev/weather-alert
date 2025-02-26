<?php

namespace App\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\User;
use App\Notifications\EmailWeatherAlertNotification;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class PushSmartAlert
{
    public function handle(NotifySmartAlertDTO $DTO, Closure $next)
    {
        $collection = $DTO->getUsers()->where('email_notification', true);

        $collection->map(function (User $user) use ($DTO) {
            try {
                $user->notify(new EmailWeatherAlertNotification(
                    $DTO->getLocation()->getFullAddressAttribute(),
                    [
                        'uv' => $DTO->getCurrentUVIndex(),
                        'precipitation' => $DTO->getCurrentPrecipitation(),
                    ]
                ));
            } catch (Throwable $exception) {
                Log::error('Push sent error userId '.$user->id.$exception->getMessage());
            }
        });

        return $next($DTO);
    }
}
