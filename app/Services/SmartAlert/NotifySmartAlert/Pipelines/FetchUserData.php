<?php

namespace App\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\Location;
use App\Models\User;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use Closure;

class FetchUserData
{
    public function handle(NotifySmartAlertDTO $DTO, Closure $next)
    {
        $userIds = $DTO->getUserIds();
        $users = User::query()
            ->select(['id', 'email', 'push_notification', 'email_notification'])
            ->whereIn('id', $userIds)->get();

        $DTO->setUsers($users);
        $DTO->setLocation(Location::find($DTO->getLocationId()));

        return $next($DTO);
    }
}
