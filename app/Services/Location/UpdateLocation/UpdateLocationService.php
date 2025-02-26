<?php

namespace App\Services\Location\UpdateLocation;

use App\Services\Base\BasePipelineService;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\Pipelines\DispatchWeatherCondition;
use App\Services\Location\UpdateLocation\Pipelines\StoreLocation;
use App\Services\Location\UpdateLocation\Pipelines\ValidationLocation;
use Illuminate\Support\Facades\Log;

class UpdateLocationService extends BasePipelineService
{
    protected const HANDLERS = [
        ValidationLocation::class,
        StoreLocation::class,
        DispatchWeatherCondition::class,
    ];

    public function process(UpdateLocationDTO $dto): array
    {
        try {
            Log::info('Starting location update process', [
                'user_id' => $dto->getUser()->id,
                'locations_count' => count($dto->getLocations()),
            ]);

            $this->processPipeline($dto);

            Log::info('Location update completed successfully');

            return $dto->getLocations();
        } catch (\Exception $e) {
            Log::error('Location update failed', [
                'error' => $e->getMessage(),
                'user_id' => $dto->getUser()->id,
            ]);
            throw $e;
        }
    }
}
