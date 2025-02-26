<?php

namespace App\Services\Location\UpdateLocation\DTO;

use App\DTO\BaseDTO;
use App\Models\User;

class UpdateLocationDTO extends BaseDTO
{
    private array $locations;

    private User $user;

    protected function fill(array $data): void
    {
        if ($data['locations'] ?? null) {
            $this->setLocations($data['locations']);
        }
        if ($data['user'] ?? null) {
            $this->setUser($data['user']);
        }
    }

    public function isValid(): bool
    {
        return ! empty($this->locations);
    }

    public function setLocations(array $value): self
    {
        $this->locations = $value;

        return $this;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function setUser(User $value): self
    {
        $this->user = $value;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
