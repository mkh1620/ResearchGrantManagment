<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Milestone>
 */
class MilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'research_grant_id' => \App\Models\ResearchGrant::factory(),
            'name' => fake()->sentence(),
            'target_completion_date' => fake()->dateTimeBetween('now', '+2 years'),
            'deliverable' => fake()->paragraph(),
            'status' => fake()->randomElement(['Pending', 'Completed']),
            'remark' => fake()->optional()->paragraph(),
            'date_updated' => now(),
        ];
    }
}
