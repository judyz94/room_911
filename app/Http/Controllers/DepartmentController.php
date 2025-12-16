<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->success(Department::all());
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());

        return $this->success($department, 'Department created');
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());

        return $this->success($department, 'Department updated');
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return $this->success(null, 'Department deleted');
    }

    public function view(): Response
    {
        return Inertia::render('Departments/Index');
    }
}
