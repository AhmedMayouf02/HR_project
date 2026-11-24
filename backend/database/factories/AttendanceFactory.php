<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'date' => fake()->unique()->dateTimeBetween('-60 days', 'now')->format('Y-m-d'),
            'check_in' => '08:'.fake()->numberBetween(0, 45),
            'check_out' => '16:'.fake()->numberBetween(10, 59),
            'status' => AttendanceStatus::Present,
        ];
    }
}
