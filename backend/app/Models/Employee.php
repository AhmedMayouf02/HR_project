<?php

namespace App\Models;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'employee_number', 'first_name', 'last_name', 'email', 'phone',
    'national_id', 'gender', 'date_of_birth', 'department_id', 'position_id',
    'manager_id', 'hire_date', 'employment_type', 'status', 'basic_salary', 'address',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, SoftDeletes;

    /** @var array<string, mixed> */
    protected $attributes = [
        'status' => 'active',
        'employment_type' => 'full_time',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'employment_type' => EmploymentType::class,
            'status' => EmploymentStatus::class,
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'basic_salary' => 'decimal:2',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => "{$this->first_name} {$this->last_name}");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
