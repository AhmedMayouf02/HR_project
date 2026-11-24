<?php

namespace App\Http\Requests\LeaveType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveTypeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100', 'unique:leave_types,name'],
            'days_per_year' => ['required', 'integer', 'min:0', 'max:365'],
            'is_paid' => ['sometimes', 'boolean'],
        ];
    }
}
