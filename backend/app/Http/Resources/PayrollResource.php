<?php

namespace App\Http\Resources;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Payroll */
class PayrollResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'year' => $this->year,
            'month' => $this->month,
            'basic_salary' => $this->basic_salary,
            'allowances' => $this->allowances,
            'deductions' => $this->deductions,
            'net_salary' => $this->net_salary,
            'status' => $this->status?->value,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'notes' => $this->notes,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
