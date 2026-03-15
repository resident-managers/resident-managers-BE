<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthInsurance>
 */
class HealthInsuranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'                => fake()->unique()->numerify('DN40120######'),
            'healthcare_facility' => fake()->company(),
            'issued_date'         => fake()->date(),
            'expiry_date'         => fake()->dateTimeBetween('+1 month', '+5 years')->format('Y-m-d'),
        ];
    }
}
