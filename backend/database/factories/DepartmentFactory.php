<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Human Resources', 'Finance', 'IT', 'Operations', 'Sales', 'Marketing', 'Legal', 'Procurement']),
            'code' => strtoupper(fake()->unique()->bothify('DEP-###')),
            'description' => fake()->sentence(),
        ];
    }
}
