<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->success(
            Employee::with('department')->get()
        );
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = Employee::create($request->validated());

        return $this->success($employee, 'Employee created');
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee->update($request->validated());

        return $this->success($employee, 'Employee updated');
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return $this->success(null, 'Employee deleted');
    }
}
