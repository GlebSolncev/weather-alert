<?php

namespace App\Services\Weather\SyncWeather\DTO;

use App\DTO\BaseDTO;

class WeatherParamDTO extends BaseDTO
{
    private int $locationId;

    private float $latitude;

    private float $longitude;

    private array $providerData = [];

    private array $result = [];

    protected function fill(array $data): void
    {
        if (isset($data['id']) === true) {
            $this->setLocationId($data['id']);
        }

        if (isset($data['latitude']) === true) {
            $this->setLatitude($data['latitude']);
        }

        if (isset($data['longitude']) === true) {
            $this->setLongitude($data['longitude']);
        }
    }

    public function isValid(): bool
    {
        return isset($this->locationId, $this->latitude, $this->longitude);
    }

    public function setProviderData(string $key, array $data): self
    {
        $this->providerData[$key] = $data;

        return $this;
    }

    public function getProviderData(): array
    {
        return $this->providerData;
    }

    public function setResult(array $data): self
    {
        $this->result = $data;

        return $this;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function setLocationId(int $value): self
    {
        $this->locationId = $value;

        return $this;
    }

    public function getLocationId(): float
    {
        return $this->locationId;
    }

    public function setLatitude(float $value): self
    {
        $this->latitude = $value;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLongitude(float $value): self
    {
        $this->longitude = $value;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
