<?php

namespace App\Models;

use App\Enums\DocumentType;
use Database\Factories\EmployeeDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'type', 'title', 'file_path', 'expires_at'])]
class EmployeeDocument extends Model
{
    /** @use HasFactory<EmployeeDocumentFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'expires_at' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
