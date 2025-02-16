<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => $this->faker->numberBetween(1, 5),
            'hall_id' => $this->faker->numberBetween(1, 5),
            'start_time' => $this->faker->datetime(),
            'end_time' => $this->faker->datetime(),
        ];
    }
}
