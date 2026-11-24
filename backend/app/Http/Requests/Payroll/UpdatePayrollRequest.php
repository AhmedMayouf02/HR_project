<?php

namespace App\Http\Requests\Payroll;

use App\Enums\PayrollStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayrollRequest extends FormRequest
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
            'basic_salary' => ['sometimes', 'numeric', 'min:0'],
            'allowances' => ['sometimes', 'numeric', 'min:0'],
            'deductions' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', Rule::enum(PayrollStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
