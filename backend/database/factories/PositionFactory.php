<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->sentence(),
            'min_salary' => 5000,
            'max_salary' => 15000,
        ];
    }
}
