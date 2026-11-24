<?php

namespace Database\Factories;

use App\Enums\PayrollStatus;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'year' => (int) now()->format('Y'),
            'month' => fake()->numberBetween(1, 12),
            'basic_salary' => 10000,
            'allowances' => 2000,
            'deductions' => 500,
            'net_salary' => 11500,
            'status' => PayrollStatus::Draft,
        ];
    }
}
