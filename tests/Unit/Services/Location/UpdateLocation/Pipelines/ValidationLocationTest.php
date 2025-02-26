<?php

namespace Tests\Unit\Services\Location\UpdateLocation\Pipelines;

use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\Pipelines\ValidationLocation;
use App\Services\Location\UpdateLocation\Utils\OpenStreetMapService;
use Mockery;
use Tests\Unit\Services\TestCase;

class ValidationLocationTest extends TestCase
{
    private ValidationLocation $pipeline;

    private OpenStreetMapService $mapService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapService = Mockery::mock(OpenStreetMapService::class);
        $this->pipeline = new ValidationLocation($this->mapService);
    }

    public function test_validates_locations(): void
    {
        $locations = [
            $this->createLocationData(),
        ];

        $locationInfo = [
            'country' => 'Ukraine',
            'city' => 'Odessa',
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ];

        $this->mapService->shouldReceive('getLocationInfo')
            ->with('Ukraine', 'Odessa')
            ->once()
            ->andReturn($locationInfo);

        $dto = new UpdateLocationDTO([
            'locations' => $locations,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $validatedLocations = $dto->getLocations();
        $this->assertEquals($locationInfo['latitude'], $validatedLocations[0]['latitude']);
        $this->assertEquals($locationInfo['longitude'], $validatedLocations[0]['longitude']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
