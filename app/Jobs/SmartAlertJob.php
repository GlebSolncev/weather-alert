<?php

namespace App\Jobs;

use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\NotifySmartAlertService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SmartAlertJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [30, 60, 120];

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $param,
    ) {
        $this->queue = 'smart-alert';
    }

    /**
     * Execute the job.
     */
    public function handle(NotifySmartAlertService $service): void
    {
        try {
            Log::debug('SmartAlertJob: Starting notification process', [
                'location_id' => $this->param['locationId'] ?? null,
            ]);

            $dto = new NotifySmartAlertDTO($this->param);
            if (! $dto->isValid()) {
                throw new \InvalidArgumentException('Invalid notification parameters');
            }

            $service->process($dto);

            Log::debug('SmartAlertJob: Notification completed successfully');
        } catch (\Exception $e) {
            Log::error('SmartAlertJob: Notification failed', [
                'error' => $e->getMessage(),
                'params' => $this->param,
            ]);
            throw $e;
        }
    }
}
