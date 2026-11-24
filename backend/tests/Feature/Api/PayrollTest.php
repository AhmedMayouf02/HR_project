<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    public function test_it_creates_a_payroll_from_the_employee_salary(): void
    {
        $employee = Employee::factory()->create(['basic_salary' => 10000]);

        $this->postJson('/api/v1/payrolls', [
            'employee_id' => $employee->id,
            'year' => 2026,
            'month' => 11,
            'allowances' => 1500,
            'deductions' => 250.50,
        ])->assertCreated()
            ->assertJsonPath('data.basic_salary', '10000.00')
            ->assertJsonPath('data.net_salary', '11249.50')
            ->assertJsonPath('data.status', 'draft');
    }

    public function test_it_rejects_a_duplicate_month(): void
    {
        $payroll = Payroll::factory()->create(['year' => 2026, 'month' => 11]);

        $this->postJson('/api/v1/payrolls', [
            'employee_id' => $payroll->employee_id,
            'year' => 2026,
            'month' => 11,
        ])->assertUnprocessable()->assertJsonValidationErrors('month');
    }

    public function test_marking_as_paid_recalculates_net_and_sets_paid_at(): void
    {
        $payroll = Payroll::factory()->create();

        $this->patchJson("/api/v1/payrolls/{$payroll->id}", ['deductions' => 1000, 'status' => 'paid'])
            ->assertOk()
            ->assertJsonPath('data.net_salary', '11000.00')
            ->assertJsonPath('data.status', 'paid')
            ->assertJsonPath('data.paid_at', fn ($v) => $v !== null);
    }
}
