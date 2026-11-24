<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveType\StoreLeaveTypeRequest;
use App\Http\Requests\LeaveType\UpdateLeaveTypeRequest;
use App\Http\Resources\LeaveTypeResource;
use App\Models\LeaveType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LeaveTypeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return LeaveTypeResource::collection(LeaveType::orderBy('name')->get());
    }

    public function store(StoreLeaveTypeRequest $request): LeaveTypeResource
    {
        return new LeaveTypeResource(LeaveType::create($request->validated()));
    }

    public function show(LeaveType $leaveType): LeaveTypeResource
    {
        return new LeaveTypeResource($leaveType);
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType): LeaveTypeResource
    {
        $leaveType->update($request->validated());

        return new LeaveTypeResource($leaveType);
    }

    public function destroy(LeaveType $leaveType): Response
    {
        $leaveType->delete();

        return response()->noContent();
    }
}
