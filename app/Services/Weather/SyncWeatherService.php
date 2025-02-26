<?php

namespace App\Services\Weather;

use App\Jobs\SyncWeatherJob;
use App\Models\Location;
use App\Services\Base\BasePipelineService;
use Exception;
use Illuminate\Support\Facades\Log;

class SyncWeatherService extends BasePipelineService
{
    public function dispatchSync(): void
    {
        try {
            Location::query()
                ->select(['latitude', 'longitude', 'id'])
                ->each(function ($location) {
                    SyncWeatherJob::dispatch($location->toArray());
                });

            Log::info('Weather sync dispatched successfully');
        } catch (Exception $e) {
            Log::error('Weather sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
