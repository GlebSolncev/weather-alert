<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $cities = [
            'Ukraine' => ['Odessa', 'Kyiv', 'Lviv', 'Kharkiv', 'Dnipro'],
            'Poland' => ['Warsaw', 'Krakow', 'Gdansk', 'Wroclaw', 'Poznan'],
            'Germany' => ['Berlin', 'Munich', 'Hamburg', 'Frankfurt', 'Cologne'],
        ];

        $country = $this->faker->randomElement(array_keys($cities));
        $city = $this->faker->randomElement($cities[$country]);

        return [
            'country' => $country,
            'city' => $city,
            'latitude' => $this->faker->unique()->latitude(44, 53),
            'longitude' => $this->faker->unique()->longitude(14, 24),
        ];
    }

    /**
     * Указать конкретный город
     */
    public function city(string $country, string $city): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
            'city' => $city,
        ]);
    }

    /**
     * Указать конкретную страну
     */
    public function country(string $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
        ]);
    }
}
