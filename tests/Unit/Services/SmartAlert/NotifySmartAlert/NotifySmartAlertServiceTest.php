<?php

namespace Tests\Unit\Services\SmartAlert\NotifySmartAlert;

use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use App\Services\SmartAlert\NotifySmartAlert\NotifySmartAlertService;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\EmailSmartAlert;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\FetchUserData;
use App\Services\SmartAlert\NotifySmartAlert\Pipelines\PushSmartAlert;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Tests\Unit\Services\TestCase;

class NotifySmartAlertServiceTest extends TestCase
{
    private NotifySmartAlertService $service;

    private Pipeline $pipeline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pipeline = $this->createMock(Pipeline::class);
        $this->service = new NotifySmartAlertService($this->pipeline);
        Notification::fake();
    }

    public function test_process_executes_pipeline(): void
    {
        $dto = new NotifySmartAlertDTO([
            'weatherData' => [
                'uv_index' => 5.7,
                'precipitation' => 0.5,
            ],
            'locationId' => 1,
            'userIds' => [1, 2, 3],
        ]);

        Log::shouldReceive('info')->twice();

        $this->pipeline->expects($this->once())
            ->method('send')
            ->with($dto)
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('through')
            ->with([
                FetchUserData::class,
                EmailSmartAlert::class,
                PushSmartAlert::class,
            ])
            ->willReturnSelf();

        $this->pipeline->expects($this->once())
            ->method('thenReturn')
            ->willReturn($dto);

        $this->service->process($dto);
    }

    public function test_process_handles_exception(): void
    {
        $dto = new NotifySmartAlertDTO([
            'weatherData' => [
                'uv_index' => 5.7,
                'precipitation' => 0.5,
            ],
            'locationId' => 1,
            'userIds' => [1, 2, 3],
        ]);

        Log::shouldReceive('info')->once();
        Log::shouldReceive('error')->once();

        $this->pipeline->method('send')->willThrowException(new \Exception('Test error'));

        $this->expectException(\Exception::class);

        $this->service->process($dto);
    }
}
