<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::first();

        if (!$department) {
            return;
        }

        Employee::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'internal_id' => 'EMP-001',
            'department_id' => $department->id,
            'has_access' => true,
        ]);

        Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'internal_id' => 'EMP-002',
            'department_id' => $department->id,
            'has_access' => false,
        ]);
    }
}
