<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin'
        ]);

        // Create random users
        User::factory()->admin()->count(2)->create();
        User::factory()->staff()->count(5)->create();
        // Note: Academicians are created through AcademicianFactory
    }
}
