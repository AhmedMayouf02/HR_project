<?php

namespace App\Http\Requests\Employee;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'national_id' => ['nullable', 'string', 'max:50', 'unique:employees,national_id'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'hire_date' => ['required', 'date'],
            'employment_type' => ['sometimes', Rule::enum(EmploymentType::class)],
            'status' => ['sometimes', Rule::enum(EmploymentStatus::class)],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'address' => ['nullable', 'string'],
        ];
    }
}
