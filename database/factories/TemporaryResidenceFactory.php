<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporaryResidence>
 */
class TemporaryResidenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address'   => fake()->address(),
            'host_name' => fake()->name(),
            'from_date' => fake()->date(),
            'to_date'   => null,
            'reason'    => fake()->sentence(),
        ];
    }
}
