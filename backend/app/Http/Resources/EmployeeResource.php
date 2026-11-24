<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Employee */
class EmployeeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_number' => $this->employee_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'national_id' => $this->national_id,
            'gender' => $this->gender?->value,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'hire_date' => $this->hire_date?->toDateString(),
            'employment_type' => $this->employment_type?->value,
            'status' => $this->status?->value,
            'basic_salary' => $this->basic_salary,
            'address' => $this->address,
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'position_id' => $this->position_id,
            'manager_id' => $this->manager_id,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'position' => new PositionResource($this->whenLoaded('position')),
            'manager' => new EmployeeResource($this->whenLoaded('manager')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
