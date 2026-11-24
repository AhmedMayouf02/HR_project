<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmployeeDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uploads_and_deletes_an_employee_document(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());
        $employee = Employee::factory()->create();

        $response = $this->postJson("/api/v1/employees/{$employee->id}/documents", [
            'type' => 'contract',
            'title' => 'Contract 2026',
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ])->assertCreated();

        $path = $response->json('data.file_path');
        Storage::disk('local')->assertExists($path);

        $this->deleteJson('/api/v1/documents/'.$response->json('data.id'))->assertNoContent();
        Storage::disk('local')->assertMissing($path);
    }
}
