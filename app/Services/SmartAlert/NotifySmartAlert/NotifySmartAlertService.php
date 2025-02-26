<?php

namespace App\Services\SmartAlert\NotifySmartAlert;

use App\Services\Base\BasePipelineService;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\EmailSmartAlert;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\FetchUserData;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\PushSmartAlert;
use Illuminate\Support\Facades\Log;

class NotifySmartAlertService extends BasePipelineService
{
    protected const HANDLERS = [
        FetchUserData::class,
        EmailSmartAlert::class,
        PushSmartAlert::class,
    ];

    public function process(NotifySmartAlertDTO $dto): void
    {
        try {
            Log::info('Starting smart alert notification process', [
                'location_id' => $dto->getLocationId(),
                'users_count' => count($dto->getUserIds()),
            ]);

            $this->processPipeline($dto);

            Log::info('Smart alert notification completed successfully');
        } catch (\Exception $e) {
            Log::error('Smart alert notification failed', [
                'error' => $e->getMessage(),
                'location_id' => $dto->getLocationId(),
            ]);
            throw $e;
        }
    }
}
