<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StorePayrollRequest;
use App\Http\Requests\Payroll\UpdatePayrollRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PayrollController extends Controller
{
    public function __construct(private readonly PayrollService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $payrolls = Payroll::with('employee')
            ->when($request->integer('employee_id'), fn ($q, $id) => $q->where('employee_id', $id))
            ->when($request->integer('year'), fn ($q, $year) => $q->where('year', $year))
            ->when($request->integer('month'), fn ($q, $month) => $q->where('month', $month))
            ->orderByDesc('year')->orderByDesc('month')
            ->paginate(config('hr.per_page'));

        return PayrollResource::collection($payrolls);
    }

    public function store(StorePayrollRequest $request): PayrollResource
    {
        return new PayrollResource($this->service->create($request->validated()));
    }

    public function show(Payroll $payroll): PayrollResource
    {
        return new PayrollResource($payroll->load('employee'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll): PayrollResource
    {
        return new PayrollResource($this->service->update($payroll, $request->validated()));
    }

    public function destroy(Payroll $payroll): Response
    {
        $payroll->delete();

        return response()->noContent();
    }
}
