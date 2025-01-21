<?php

namespace Database\Seeders;

use App\Models\ResearchGrant;
use App\Models\Milestone;
use App\Models\Academician;
use Illuminate\Database\Seeder;

class ResearchGrantTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create research grants with milestones
        ResearchGrant::factory()
            ->count(15)
            ->has(Milestone::factory()->count(5))
            ->create()
            ->each(function ($grant) {
                // Attach 2-4 random academicians as team members (excluding the project leader)
                $teamMembers = Academician::where('id', '!=', $grant->academician_id)
                    ->inRandomOrder()
                    ->take(rand(2, 4))
                    ->get();
                
                $grant->teamMembers()->attach($teamMembers);
            });
    }
}
