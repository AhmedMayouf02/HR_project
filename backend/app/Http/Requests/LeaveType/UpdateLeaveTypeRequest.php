<?php

namespace App\Http\Requests\LeaveType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveTypeRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('leave_types', 'name')->ignore($this->route('leave_type'))],
            'days_per_year' => ['sometimes', 'required', 'integer', 'min:0', 'max:365'],
            'is_paid' => ['sometimes', 'boolean'],
        ];
    }
}
