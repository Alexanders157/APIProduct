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
        $startTime = $this->faker->dateTimeBetween('-1 week', '+1 week');
        $endTime = clone $startTime;
        $endTime->modify('+2 hours');

        return [
            'movie_id' => $this->faker->numberBetween(1, 5),
            'hall_id' => $this->faker->numberBetween(1, 5),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $this->faker->randomFloat(2, 10, 50),
        ];
    }
}
