<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AttendanceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $attendances = Attendance::with('employee')
            ->when($request->integer('employee_id'), fn ($q, $id) => $q->where('employee_id', $id))
            ->when($request->date('from'), fn ($q, $from) => $q->whereDate('date', '>=', $from))
            ->when($request->date('to'), fn ($q, $to) => $q->whereDate('date', '<=', $to))
            ->latest('date')
            ->paginate(config('hr.per_page'));

        return AttendanceResource::collection($attendances);
    }

    public function store(StoreAttendanceRequest $request): AttendanceResource
    {
        return new AttendanceResource(Attendance::create($request->validated()));
    }

    public function show(Attendance $attendance): AttendanceResource
    {
        return new AttendanceResource($attendance->load('employee'));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): AttendanceResource
    {
        $attendance->update($request->validated());

        return new AttendanceResource($attendance);
    }

    public function destroy(Attendance $attendance): Response
    {
        $attendance->delete();

        return response()->noContent();
    }
}
