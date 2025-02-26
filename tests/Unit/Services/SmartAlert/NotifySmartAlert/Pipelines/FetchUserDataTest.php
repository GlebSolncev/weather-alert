<?php

namespace Tests\Unit\Services\SmartAlert\NotifySmartAlert\Pipelines;

use App\Models\Location;
use App\Models\User;
use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\FetchUserData;
use Tests\Unit\Services\TestCase;

class FetchUserDataTest extends TestCase
{
    private FetchUserData $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new FetchUserData;
    }

    public function test_fetches_users_data(): void
    {
        Location::factory()->create();
        $users = User::factory()->count(3)->create([
            'email_notification' => true,
        ]);

        $userIds = $users->pluck('id')->toArray();

        $dto = new NotifySmartAlertDTO([
            'weatherData' => $this->createWeatherData(),
            'locationId' => 1,
            'userIds' => $userIds,
        ]);

        $this->pipeline->handle($dto, function ($dto) {
            return $dto;
        });

        $this->assertEquals(3, $dto->getUsers()->count());
        $this->assertEquals($userIds, $dto->getUsers()->pluck('id')->toArray());
    }
}
