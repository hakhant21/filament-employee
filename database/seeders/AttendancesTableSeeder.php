<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attendances')->delete();

        $employees = Employee::pluck('id')->toArray();

        $currentMonth = Carbon::now()->month;

        $currentYear = Carbon::now()->year;

        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);

        $endOfMonth = Carbon::create($currentYear, $currentMonth, $startOfMonth->daysInMonth);

        $dates = [];

        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            if ($date->isWeekday()) {
                $dates[] = $date->toDateString();
            }
        }

        foreach ($employees as $employee) {
            foreach ($dates as $date) {
                DB::table('attendances')->insert([
                    'employee_id' => $employee,
                    'date' => $date,
                    'status' => 'Present',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
