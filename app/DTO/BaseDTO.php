<?php

namespace App\DTO;

use App\Contracts\DTOs\DTOInterface;

abstract class BaseDTO implements DTOInterface
{
    public function __construct(array $data)
    {
        $this->fill($data);
    }

    abstract protected function fill(array $data): void;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
