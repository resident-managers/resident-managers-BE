<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name'        => fake()->name(),
            'gender'           => fake()->randomElement(['MALE', 'FEMALE']),
            'date_of_birth'    => fake()->date(),
            'phone'            => fake()->numerify('0#########'),
            'national_id'      => fake()->unique()->numerify('0##########'),
            'address'          => fake()->address(),
            'permanent_address' => fake()->address(),
            'occupation'       => fake()->jobTitle(),
            'ethnicity'        => 'Kinh',
            'religion'         => 'Không',
            'education_level'  => 'Đại học',
            'note'             => null,
            'type'             => 'permanent',
        ];
    }
}
