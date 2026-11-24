<?php

namespace App\Http\Requests\Employee;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'employee_number' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('employees', 'employee_number')->ignore($this->route('employee'))],
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($this->route('employee'))],
            'phone' => ['nullable', 'string', 'max:30'],
            'national_id' => ['nullable', 'string', 'max:50', Rule::unique('employees', 'national_id')->ignore($this->route('employee'))],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'hire_date' => ['sometimes', 'required', 'date'],
            'employment_type' => ['sometimes', Rule::enum(EmploymentType::class)],
            'status' => ['sometimes', Rule::enum(EmploymentStatus::class)],
            'basic_salary' => ['sometimes', 'required', 'numeric', 'min:0'],
            'address' => ['nullable', 'string'],
        ];
    }
}
