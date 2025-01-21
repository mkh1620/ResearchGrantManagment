<?php

namespace Database\Seeders;

use App\Models\ResearchGrant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpdateGrantStartDatesSeeder extends Seeder
{
    public function run(): void
    {
        ResearchGrant::whereNull('start_date')->each(function ($grant) {
            $grant->update([
                'start_date' => Carbon::now()->subMonths(rand(0, 24))->format('Y-m-d')
            ]);
        });
    }
}
