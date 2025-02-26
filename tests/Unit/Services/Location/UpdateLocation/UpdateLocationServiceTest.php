<?php

namespace Tests\Unit\Services\Location\UpdateLocation;

use App\Models\User;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\UpdateLocationService;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Tests\Unit\Services\TestCase;

class UpdateLocationServiceTest extends TestCase
{
    private UpdateLocationService $service;

    private Pipeline $pipeline;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pipeline = $this->createMock(Pipeline::class);
        $this->service = new UpdateLocationService($this->pipeline);
    }

    public function test_process_executes_pipeline(): void
    {
        Log::shouldReceive('info')->twice();

        $dto = new UpdateLocationDTO([
            'locations' => [
                ['city' => 'Moscow', 'country' => 'Russia'],
            ],
            'user' => User::factory()->create(),
        ]);

        $this->pipeline->expects($this->once())
            ->method('send')
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('through')
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('thenReturn')
            ->willReturn($dto);

        $result = $this->service->process($dto);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }
}
