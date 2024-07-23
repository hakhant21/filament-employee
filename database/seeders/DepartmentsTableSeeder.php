<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->delete();

        $departments = array(
            array('name' => "Human Resources",'created_at' => now(), 'updated_at' => now()),
            array('name' => "Finance",'created_at' => now(), 'updated_at' => now()),
            array('name' => "IT",'created_at' => now(), 'updated_at' => now()),
            array('name' => "Marketing",'created_at' => now(), 'updated_at' => now()),
            array('name' => "Sales",'created_at' => now(), 'updated_at' => now())
        );

        DB::table('departments')->insert($departments);
    }
}
