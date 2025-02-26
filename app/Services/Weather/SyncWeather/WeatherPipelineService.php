<?php

namespace App\Services\Weather\SyncWeather;

use App\Services\Base\BasePipelineService;
use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Pipelines\CalculateAverage;
use App\Services\Weather\SyncWeather\Pipelines\DispatchSmartAlert;
use App\Services\Weather\SyncWeather\Pipelines\FetchWeatherData;
use App\Services\Weather\SyncWeather\Pipelines\StoreWeatherLocation;
use Illuminate\Support\Facades\Log;

class WeatherPipelineService extends BasePipelineService
{
    protected const HANDLERS = [
        FetchWeatherData::class,
        CalculateAverage::class,
        StoreWeatherLocation::class,
        DispatchSmartAlert::class,
    ];

    public function process(WeatherParamDTO $dto): void
    {
        try {
            Log::info('Starting weather pipeline process', [
                'location_id' => $data['id'] ?? null,
            ]);

            if (! $dto->isValid()) {
                throw new \InvalidArgumentException('Invalid weather parameters provided');
            }

            $this->processPipeline($dto);

            Log::info('Weather pipeline completed successfully');
        } catch (\Exception $e) {
            Log::error('Weather pipeline failed', [
                'error' => $e->getMessage(),
                'location_id' => $data['id'] ?? null,
            ]);
            throw $e;
        }
    }
}
