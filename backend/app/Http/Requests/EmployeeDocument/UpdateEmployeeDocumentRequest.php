<?php

namespace App\Http\Requests\EmployeeDocument;

use App\Enums\DocumentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeDocumentRequest extends FormRequest
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
            'type' => ['sometimes', Rule::enum(DocumentType::class)],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
