<?php

namespace App\Jobs;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\WeatherPipelineService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncWeatherJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [30, 60, 120];

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $locationData,
    ) {
        $this->queue = 'sync-weather';
    }

    /**
     * Execute the job.
     */
    public function handle(WeatherPipelineService $pipelineService): void
    {
        try {
            Log::info('Starting weather sync for location', [
                'location_id' => $this->locationData['id'],
            ]);

            $pipelineService->process(new WeatherParamDTO($this->locationData));

            Log::info('Weather sync completed', [
                'location_id' => $this->locationData['id'],
            ]);
        } catch (\Exception $e) {
            Log::error('Weather sync failed', [
                'location_id' => $this->locationData['id'],
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
