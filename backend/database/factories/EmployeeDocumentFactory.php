<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployeeDocument>
 */
class EmployeeDocumentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'type' => DocumentType::Contract,
            'title' => 'Employment contract',
            'file_path' => 'employees/demo/contract.pdf',
            'expires_at' => null,
        ];
    }
}
