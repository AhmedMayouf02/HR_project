<?php

namespace App\Services;

use App\Enums\PayrollStatus;
use App\Models\Employee;
use App\Models\Payroll;

class PayrollService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Payroll
    {
        $employee = Employee::findOrFail($data['employee_id']);

        $data['basic_salary'] ??= $employee->basic_salary;
        $data['allowances'] ??= 0;
        $data['deductions'] ??= 0;
        $data['net_salary'] = $this->net($data['basic_salary'], $data['allowances'], $data['deductions']);
        $data['status'] = PayrollStatus::Draft;

        return Payroll::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Payroll $payroll, array $data): Payroll
    {
        $payroll->fill($data);
        $payroll->net_salary = $this->net($payroll->basic_salary, $payroll->allowances, $payroll->deductions);

        if ($payroll->isDirty('status') && $payroll->status === PayrollStatus::Paid) {
            $payroll->paid_at ??= now();
        }

        $payroll->save();

        return $payroll;
    }

    public function net(string|float|int $basic, string|float|int $allowances, string|float|int $deductions): string
    {
        return number_format((float) $basic + (float) $allowances - (float) $deductions, 2, '.', '');
    }
}
