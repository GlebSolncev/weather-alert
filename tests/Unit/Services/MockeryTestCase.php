<?php

namespace Tests\Unit\Services;

use Mockery;

abstract class MockeryTestCase extends TestCase
{
    protected function tearDown(): void
    {
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
        parent::tearDown();
    }
}
