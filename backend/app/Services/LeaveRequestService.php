<?php

namespace App\Services;

use App\Enums\EmploymentStatus;
use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{
    /**
     * Count working days in a range, skipping the weekend days in config/hr.php.
     */
    public function workingDays(CarbonInterface $start, CarbonInterface $end): int
    {
        $weekend = config('hr.weekend_days');
        $days = 0;

        for ($date = $start->copy(); $date->lte($end); $date = $date->addDay()) {
            if (! in_array($date->dayOfWeek, $weekend, true)) {
                $days++;
            }
        }

        return $days;
    }

    public function approve(LeaveRequest $leaveRequest, User $reviewer, ?string $note = null): LeaveRequest
    {
        $this->ensurePending($leaveRequest);

        return DB::transaction(function () use ($leaveRequest, $reviewer, $note) {
            $leaveRequest->update([
                'status' => LeaveRequestStatus::Approved,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'review_note' => $note,
            ]);

            if ($leaveRequest->start_date->lte(today()) && $leaveRequest->end_date->gte(today())) {
                $leaveRequest->employee()->update(['status' => EmploymentStatus::OnLeave]);
            }

            return $leaveRequest;
        });
    }

    public function reject(LeaveRequest $leaveRequest, User $reviewer, ?string $note = null): LeaveRequest
    {
        $this->ensurePending($leaveRequest);

        $leaveRequest->update([
            'status' => LeaveRequestStatus::Rejected,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_note' => $note,
        ]);

        return $leaveRequest;
    }

    public function ensurePending(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->status !== LeaveRequestStatus::Pending) {
            throw ValidationException::withMessages([
                'status' => 'Only pending leave requests can be changed.',
            ]);
        }
    }
}
