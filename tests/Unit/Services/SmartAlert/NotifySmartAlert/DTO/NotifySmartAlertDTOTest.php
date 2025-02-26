<?php

namespace Tests\Unit\Services\SmartAlert\NotifySmartAlert\DTO;

use App\Services\SmartAlert\NotifySmartAlert\DTO\NotifySmartAlertDTO;
use Tests\Unit\Services\TestCase;

class NotifySmartAlertDTOTest extends TestCase
{
    public function test_dto_validates_correctly(): void
    {
        $dto = new NotifySmartAlertDTO([
            'weatherData' => [
                'uv_index' => 5,
                'precipitation' => 10,
            ],
            'locationId' => 1,
            'userIds' => [1, 2],
        ]);

        $this->assertTrue($dto->isValid());
    }

    public function test_dto_invalid_without_required_data(): void
    {
        $dto = new NotifySmartAlertDTO([
            'weatherData' => [],
        ]);

        $this->assertFalse($dto->isValid());
    }
}
