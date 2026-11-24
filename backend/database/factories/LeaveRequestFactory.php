<?php

namespace Database\Factories;

use App\Enums\LeaveRequestStatus;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date' => $start = fake()->dateTimeBetween('+1 day', '+30 days'),
            'end_date' => (clone $start)->modify('+2 days'),
            'days' => 3,
            'reason' => fake()->sentence(),
            'status' => LeaveRequestStatus::Pending,
        ];
    }
}
