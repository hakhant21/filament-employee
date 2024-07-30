<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class PayrollService
{
    public static function make(Employee $employee, $bonus)
    {
        $month = Carbon::now()->month;

        $year = Carbon::now()->year;

        $workingDays = Carbon::createFromDate($year, $month)->daysInMonth;

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $leaves = Leave::where('employee_id', $employee->id)
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orWhere(function($query) use ($year, $month) {
                $query->whereYear('end_date', $year)
                      ->whereMonth('end_date', $month);
            })
            ->get();

        $presentDays = $attendances->where('status', 'Present')->count();
        $leaveDays = $leaves->count();
        $absentDays = $workingDays - ($presentDays + $leaveDays);

        $salary = $employee->salary;
        $dailyRate = $salary / $workingDays;
        $deductions = $absentDays * $dailyRate;

        $calculatedBonus = 0;
        if($absentDays == 0) {
            $bonusPercentage = $bonus ?? 10;
            $calculatedBonus += ($bonusPercentage / 100) * $salary;
        }

        return [
            'salary' => $salary,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'net_pay' => $salary + $calculatedBonus - $deductions,
            'pay_date' => Carbon::now()
        ];
    }
}
