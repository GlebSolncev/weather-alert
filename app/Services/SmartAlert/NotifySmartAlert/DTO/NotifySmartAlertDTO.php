<?php

namespace App\Services\SmartAlert\NotifySmartAlert\DTO;

use App\DTO\BaseDTO;
use App\Models\Location;
use Illuminate\Support\Collection;

class NotifySmartAlertDTO extends BaseDTO
{
    private float $currentUVIndex;

    private float $currentPrecipitation;

    private int $locationId;

    private ?Location $location = null;

    private array $userIds;

    private ?Collection $users = null;

    protected function fill(array $data): void
    {
        if (isset($data['weatherData']['uv_index']) === true) {
            $this->setCurrentUVIndex($data['weatherData']['uv_index']);
        }

        if (isset($data['weatherData']['precipitation']) === true) {
            $this->setCurrentPrecipitation($data['weatherData']['precipitation']);
        }

        if ($data['locationId'] ?? null) {
            $this->setLocationId($data['locationId']);
        }

        if ($data['userIds'] ?? null) {
            $this->setUserIds($data['userIds']);
        }
    }

    public function isValid(): bool
    {
        return isset(
            $this->currentUVIndex,
            $this->currentPrecipitation,
            $this->locationId
        ) && ! empty($this->userIds);
    }

    public function setLocation(Location $value): self
    {
        $this->location = $value;

        return $this;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setCurrentUVIndex(float $value): self
    {
        $this->currentUVIndex = $value;

        return $this;
    }

    public function getCurrentUVIndex(): float
    {
        return $this->currentUVIndex;
    }

    public function setCurrentPrecipitation(float $value): self
    {
        $this->currentPrecipitation = $value;

        return $this;
    }

    public function getCurrentPrecipitation(): float
    {
        return $this->currentPrecipitation;
    }

    public function setLocationId(int $value): self
    {
        $this->locationId = $value;

        return $this;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setUserIds(array $value): self
    {
        $this->userIds = $value;

        return $this;
    }

    public function getUserIds(): array
    {
        return $this->userIds;
    }

    public function setUsers(Collection $collection): self
    {
        $this->users = $collection;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users ?: collect();
    }
}
