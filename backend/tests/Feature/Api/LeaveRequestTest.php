<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_it_counts_working_days_excluding_the_weekend(): void
    {
        config(['hr.weekend_days' => [5, 6]]); // Friday, Saturday

        // Sunday 2026-11-29 -> Saturday 2026-12-05 = 5 working days
        $this->postJson('/api/v1/leave-requests', [
            'employee_id' => Employee::factory()->create()->id,
            'leave_type_id' => LeaveType::factory()->create()->id,
            'start_date' => '2026-11-29',
            'end_date' => '2026-12-05',
        ])->assertCreated()
            ->assertJsonPath('data.days', 5)
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_it_approves_a_pending_request(): void
    {
        $leave = LeaveRequest::factory()->create();

        $this->postJson("/api/v1/leave-requests/{$leave->id}/approve", ['review_note' => 'OK'])
            ->assertOk()
            ->assertJsonPath('data.status', 'approved')
            ->assertJsonPath('data.reviewed_by', $this->user->id);
    }

    public function test_it_cannot_review_a_request_twice(): void
    {
        $leave = LeaveRequest::factory()->create();

        $this->postJson("/api/v1/leave-requests/{$leave->id}/reject")->assertOk();
        $this->postJson("/api/v1/leave-requests/{$leave->id}/approve")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }
}
