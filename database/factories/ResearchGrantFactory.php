<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResearchGrant>
 */
class ResearchGrantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grantProviders = ['Ministry of Education', 'National Science Foundation', 'Industry Partner', 'University Grant'];
        
        return [
            'title' => fake()->sentence(),
            'grant_amount' => fake()->randomFloat(2, 10000, 1000000),
            'grant_provider' => fake()->randomElement($grantProviders),
            'duration' => fake()->numberBetween(12, 60), // months
            'academician_id' => \App\Models\Academician::factory(),
        ];
    }
}
