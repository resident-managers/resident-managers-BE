<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporaryAbsence>
 */
class TemporaryAbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'destination' => fake()->city(),
            'from_date'   => fake()->date(),
            'to_date'     => null,
            'reason'      => fake()->sentence(),
        ];
    }
}
