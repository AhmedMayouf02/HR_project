<?php

namespace Database\Factories;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->numerify('EMP-#####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(Gender::cases()),
            'date_of_birth' => fake()->dateTimeBetween('-55 years', '-21 years'),
            'department_id' => Department::factory(),
            'position_id' => null,
            'hire_date' => fake()->dateTimeBetween('-8 years', 'now'),
            'employment_type' => EmploymentType::FullTime,
            'status' => EmploymentStatus::Active,
            'basic_salary' => fake()->numberBetween(5, 25) * 1000,
        ];
    }
}
