<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Demo data for local development. Login: admin@hr.test / password
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'HR Admin',
            'email' => 'admin@hr.test',
        ]);

        foreach ([
            ['name' => 'Annual Leave', 'days_per_year' => 30, 'is_paid' => true],
            ['name' => 'Sick Leave', 'days_per_year' => 15, 'is_paid' => true],
            ['name' => 'Emergency Leave', 'days_per_year' => 5, 'is_paid' => true],
            ['name' => 'Unpaid Leave', 'days_per_year' => 0, 'is_paid' => false],
        ] as $type) {
            LeaveType::create($type);
        }

        $departments = collect([
            ['name' => 'Human Resources', 'code' => 'HR', 'positions' => ['HR Manager', 'HR Specialist']],
            ['name' => 'Finance', 'code' => 'FIN', 'positions' => ['Accountant', 'Finance Manager']],
            ['name' => 'Information Technology', 'code' => 'IT', 'positions' => ['Software Developer', 'IT Support']],
        ])->map(function (array $data) {
            $department = Department::create(['name' => $data['name'], 'code' => $data['code']]);

            foreach ($data['positions'] as $title) {
                Position::create(['department_id' => $department->id, 'title' => $title]);
            }

            return $department;
        });

        foreach ($departments as $department) {
            Employee::factory(5)->create([
                'department_id' => $department->id,
                'position_id' => fn () => $department->positions->random()->id,
            ]);
        }

        Employee::factory()->create([
            'user_id' => $admin->id,
            'first_name' => 'HR',
            'last_name' => 'Admin',
            'email' => $admin->email,
            'department_id' => $departments->first()->id,
            'position_id' => $departments->first()->positions->first()->id,
        ]);
    }
}
