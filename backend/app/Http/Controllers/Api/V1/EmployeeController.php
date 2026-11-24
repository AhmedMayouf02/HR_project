<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $employees = Employee::with(['department', 'position'])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->when($request->integer('department_id'), fn ($q, $id) => $q->where('department_id', $id))
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->orderBy('first_name')
            ->paginate(config('hr.per_page'));

        return EmployeeResource::collection($employees);
    }

    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $employee = Employee::create($request->validated());

        return new EmployeeResource($employee->load(['department', 'position']));
    }

    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee->load(['department', 'position', 'manager']));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $employee->update($request->validated());

        return new EmployeeResource($employee->load(['department', 'position']));
    }

    public function destroy(Employee $employee): Response
    {
        $employee->delete();

        return response()->noContent();
    }
}
