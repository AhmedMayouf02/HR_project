<?php

namespace Tests\Feature\Api;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    public function test_it_lists_and_searches_employees(): void
    {
        Employee::factory()->create(['first_name' => 'Ahmed']);
        Employee::factory()->create(['first_name' => 'Sara']);

        $this->getJson('/api/v1/employees')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/employees?search=Ahm')->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Ahmed');
    }

    public function test_it_creates_an_employee(): void
    {
        $department = Department::factory()->create();

        $this->postJson('/api/v1/employees', [
            'employee_number' => 'EMP-00001',
            'first_name' => 'Ahmed',
            'last_name' => 'Ali',
            'email' => 'ahmed@example.com',
            'department_id' => $department->id,
            'hire_date' => '2026-01-01',
            'basic_salary' => 12000,
        ])->assertCreated()
            ->assertJsonPath('data.full_name', 'Ahmed Ali')
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.department.id', $department->id);
    }

    public function test_it_validates_unique_email(): void
    {
        $existing = Employee::factory()->create();

        $this->postJson('/api/v1/employees', [
            'employee_number' => 'EMP-00002',
            'first_name' => 'X',
            'last_name' => 'Y',
            'email' => $existing->email,
            'hire_date' => '2026-01-01',
            'basic_salary' => 1000,
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_it_updates_an_employee_keeping_its_own_email(): void
    {
        $employee = Employee::factory()->create();

        $this->patchJson("/api/v1/employees/{$employee->id}", [
            'email' => $employee->email,
            'status' => 'suspended',
        ])->assertOk()->assertJsonPath('data.status', 'suspended');
    }

    public function test_it_soft_deletes_an_employee(): void
    {
        $employee = Employee::factory()->create();

        $this->deleteJson("/api/v1/employees/{$employee->id}")->assertNoContent();

        $this->assertSoftDeleted($employee);
    }
}
