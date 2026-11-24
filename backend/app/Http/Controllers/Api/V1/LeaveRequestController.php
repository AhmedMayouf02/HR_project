<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\ReviewLeaveRequestRequest;
use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Http\Requests\LeaveRequest\UpdateLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class LeaveRequestController extends Controller
{
    public function __construct(private readonly LeaveRequestService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->when($request->integer('employee_id'), fn ($q, $id) => $q->where('employee_id', $id))
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(config('hr.per_page'));

        return LeaveRequestResource::collection($leaveRequests);
    }

    public function store(StoreLeaveRequestRequest $request): LeaveRequestResource
    {
        $data = $request->validated();
        $data['days'] = $this->service->workingDays(Carbon::parse($data['start_date']), Carbon::parse($data['end_date']));

        return new LeaveRequestResource(LeaveRequest::create($data)->load('leaveType'));
    }

    public function show(LeaveRequest $leaveRequest): LeaveRequestResource
    {
        return new LeaveRequestResource($leaveRequest->load(['employee', 'leaveType']));
    }

    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest): LeaveRequestResource
    {
        $this->service->ensurePending($leaveRequest);

        $leaveRequest->fill($request->validated());
        $leaveRequest->days = $this->service->workingDays($leaveRequest->start_date, $leaveRequest->end_date);
        $leaveRequest->save();

        return new LeaveRequestResource($leaveRequest->load('leaveType'));
    }

    public function destroy(LeaveRequest $leaveRequest): Response
    {
        $this->service->ensurePending($leaveRequest);

        $leaveRequest->delete();

        return response()->noContent();
    }

    public function approve(ReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): LeaveRequestResource
    {
        return new LeaveRequestResource(
            $this->service->approve($leaveRequest, $request->user(), $request->validated('review_note'))
        );
    }

    public function reject(ReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): LeaveRequestResource
    {
        return new LeaveRequestResource(
            $this->service->reject($leaveRequest, $request->user(), $request->validated('review_note'))
        );
    }
}
