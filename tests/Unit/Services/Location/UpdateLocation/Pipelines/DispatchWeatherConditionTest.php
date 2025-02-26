<?php

namespace Tests\Unit\Services\Location\UpdateLocation\Pipelines;

use App\Models\User;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\Pipelines\DispatchWeatherCondition;
use Illuminate\Support\Facades\Auth;
use Tests\Unit\Services\TestCase;

class DispatchWeatherConditionTest extends TestCase
{
    private DispatchWeatherCondition $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = new DispatchWeatherCondition;
    }

    public function test_dispatches_weather_sync(): void
    {
        Auth::login(User::factory()->create());
        $dto = new UpdateLocationDTO([
            'locations' => [[]],
            'user' => Auth::user(),
        ]);

        $result = $this->pipeline->handle($dto, fn ($dto) => $dto);

        $this->assertSame($dto, $result);
    }
}
