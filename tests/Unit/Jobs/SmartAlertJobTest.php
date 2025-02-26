<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SmartAlertJob;
use App\Services\SmartAlert\NotifySmartAlert\NotifySmartAlertService;
use Tests\TestCase;

class SmartAlertJobTest extends TestCase
{
    public function test_job_handles_notification(): void
    {
        $service = $this->createMock(NotifySmartAlertService::class);
        $service->expects($this->once())->method('process');

        $job = new SmartAlertJob([
            'userIds' => [1],
            'locationId' => 1,
            'weatherData' => ['uv_index' => 5, 'precipitation' => 1],
        ]);

        $job->handle($service);
    }
}
