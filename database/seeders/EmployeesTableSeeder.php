<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->delete();

        $employees = array(
            [
                'id' => 1,
                'fullname' => 'Htet Aung Khant',
                'gender' => 'Male',
                'country_id' => 150,
                'state_id' => 2546,
                'city_id' => 29726,
                'department_id' => 3,
                'address' => '<p>Rose Street</p>',
                'phone' => '95095905',
                'email' => 'hakhant21@gmail.com',
                'job_title' => 'Contract Manager',
                'salary' => 2000000,
                'date_of_birth' => '1995-09-10',
                'date_of_hired' => '2024-07-1'
            ],
            [
                'id' => 2,
                'fullname' => 'Htet Htet Win',
                'gender' => 'Female',
                'country_id' => 150,
                'state_id' => 2546,
                'city_id' => 29726,
                'department_id' => 4,
                'address' => '<p>Lewe, In Bu&nbsp;</p>',
                'phone' => '9459104847',
                'email' => 'hhtet24@gmail.com',
                'job_title' => 'Contract Manager',
                'salary' => 2000000,
                'date_of_birth' => '1996-04-13',
                'date_of_hired' => '2024-07-1'
            ]
        );

        DB::table('employees')->insert($employees);
    }
}
