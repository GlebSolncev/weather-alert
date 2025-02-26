<?php

namespace Database\Factories;

use App\Models\WeatherCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherConditionFactory extends Factory
{
    protected $model = WeatherCondition::class;

    public function definition(): array
    {
        return [
            'location_id' => null,
            'uv_index' => $this->faker->randomFloat(2, 0, 10),
            'precipitation' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
