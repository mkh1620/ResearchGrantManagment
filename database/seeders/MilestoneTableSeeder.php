<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Milestone;

class MilestoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Milestone::factory()
            ->count(10)
            ->create([
                'research_grant_id' => function() {
                    return \App\Models\ResearchGrant::inRandomOrder()->first()->id;
                }
            ]);
    }
}
