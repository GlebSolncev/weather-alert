<?php

namespace App\Services\Location\UpdateLocation\DTO;

use App\DTO\BaseDTO;

class StoreLocationDTO extends BaseDTO
{
    private string $country;

    private string $city;

    private ?float $latitude = null;

    private ?float $longitude = null;

    protected function fill(array $data): void
    {
        if ($data['address']['country'] ?? null) {
            $this->setCountry($data['address']['country']);
        }

        if ($data['name'] ?? null) {
            $this->setCity($data['name']);
        }

        if ($data['lat'] ?? null) {
            $this->setLatitude($data['lat']);
        }

        if ($data['lon'] ?? null) {
            $this->setLongitude($data['lon']);
        }
    }

    public function isValid(): bool
    {
        return
            isset($this->country) &&
            isset($this->city) &&
            $this->latitude !== null &&
            $this->longitude !== null;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function setCountry(string $value): self
    {
        $this->country = $value;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCity(string $value): self
    {
        $this->city = trim($value);

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setLatitude(float $value): self
    {
        $this->latitude = trim($value);

        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLongitude(float $value): self
    {
        $this->longitude = $value;

        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }
}
