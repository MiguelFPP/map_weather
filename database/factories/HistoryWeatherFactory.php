<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HistoryWeatherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_city' => "miami",
            'latitude' => 25.7743,
            'longitude' => -80.1937,
            'temp' => $this->faker->randomFloat(2, 0, 100),
            'feels_like' => $this->faker->randomFloat(2, 0, 100),
            'temp_min' => $this->faker->randomFloat(2, 0, 100),
            'temp_max' => $this->faker->randomFloat(2, 0, 100),
            'pressure' => $this->faker->randomNumber(5),
            'humidity' => $this->faker->randomNumber(5),
        ];
    }
}
