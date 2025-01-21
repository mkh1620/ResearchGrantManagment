<?php

namespace Database\Seeders;

use App\Models\Academician;
use Illuminate\Database\Seeder;

class AcademicianTableSeeder extends Seeder
{
    public function run(): void
    {
        Academician::factory()
            ->count(20)
            ->create();
    }
}
