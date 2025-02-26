<?php

namespace App\Contracts\DTOs;

interface DTOInterface
{
    public function toArray(): array;

    public function isValid(): bool;
}
