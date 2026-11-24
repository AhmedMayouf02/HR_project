<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DepartmentResource::collection(
            Department::withCount('employees')->orderBy('name')->paginate(config('hr.per_page'))
        );
    }

    public function store(StoreDepartmentRequest $request): DepartmentResource
    {
        return new DepartmentResource(Department::create($request->validated()));
    }

    public function show(Department $department): DepartmentResource
    {
        return new DepartmentResource($department->load('positions')->loadCount('employees'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): DepartmentResource
    {
        $department->update($request->validated());

        return new DepartmentResource($department);
    }

    public function destroy(Department $department): Response
    {
        $department->delete();

        return response()->noContent();
    }
}
