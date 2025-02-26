<?php

namespace App\Services\Base;

use App\Contracts\DTOs\DTOInterface;
use Illuminate\Pipeline\Pipeline;

abstract class BasePipelineService
{
    protected const HANDLERS = [];

    public function __construct(
        protected readonly Pipeline $pipeline
    ) {}

    protected function processPipeline(DTOInterface $dto): mixed
    {
        return $this->pipeline
            ->send($dto)
            ->through(static::HANDLERS)
            ->thenReturn();
    }
}
