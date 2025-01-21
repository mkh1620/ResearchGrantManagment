<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Academician>
 */
class AcademicianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colleges = ['College of Engineering', 'College of Science', 'College of Business', 'College of Arts'];
        $departments = [
            'Computer Science', 'Electrical Engineering', 'Mechanical Engineering',
            'Physics', 'Mathematics', 'Chemistry',
            'Business Administration', 'Economics', 'Accounting',
            'Fine Arts', 'Literature', 'History'
        ];
        $positions = ['Professor', 'Associate Professor', 'Assistant Professor', 'Senior Lecturer', 'Lecturer'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'college' => fake()->randomElement($colleges),
            'department' => fake()->randomElement($departments),
            'position' => fake()->randomElement($positions),
            'user_id' => User::factory()->create(['role' => 'Academician'])->id,
        ];
    }
}
