<?php

namespace Tests\Unit\Services\Location\UpdateLocation\Pipelines;

use App\Models\Location;
use App\Models\User;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\Pipelines\StoreLocation;
use Tests\Unit\Services\TestCase;

class StoreLocationTest extends TestCase
{
    private StoreLocation $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new StoreLocation;
    }

    public function test_stores_new_location(): void
    {
        $user = User::factory()->create();
        $locationData = $this->createLocationData();

        $dto = new UpdateLocationDTO([
            'locations' => [$locationData],
            'user' => $user,
        ]);

        $result = $this->pipeline->handle($dto, fn ($dto) => $dto);

        $this->assertDatabaseHas('locations', [
            'country' => $locationData['country'],
            'city' => $locationData['city'],
        ]);

        $this->assertDatabaseHas('location_user', [
            'user_id' => $user->id,
            'max_uv' => $locationData['max_uv'],
            'max_precipitation' => $locationData['max_precipitation'],
        ]);
    }

    public function test_updates_existing_location(): void
    {
        $user = User::factory()->create();
        $location = Location::factory()->create([
            'country' => 'Ukraine',
            'city' => 'Odessa',
        ]);

        $locationData = [
            [
                'country' => 'Ukraine',
                'city' => 'Odessa',
                'max_uv' => 5,
                'max_precipitation' => 10,
                'latitude' => 55.7558,
                'longitude' => 37.6173,
            ],
        ];

        $dto = new UpdateLocationDTO([
            'locations' => $locationData,
            'user' => $user,
        ]);

        $this->pipeline->handle($dto, fn ($dto) => $dto);

        $this->assertEquals(1, Location::count());
        $this->assertDatabaseHas('location_user', [
            'location_id' => $location->id,
            'user_id' => $user->id,
            'max_uv' => 5,
            'max_precipitation' => 10,
        ]);
    }
}
