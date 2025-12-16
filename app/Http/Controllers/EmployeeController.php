<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\ImportCSVEmployeeRequest;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResource
    {
        $employees = Employee::with('department')
            ->withCount('accessLogs')
            ->when($request->search, function ($q, $search) {
                $q->where('internal_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->when($request->department_id, function ($q, $dep) {
                $q->where('department_id', $dep);
            })
            ->get();

        return EmployeeResource::collection($employees);
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

    public function view(): Response
    {
        return Inertia::render('Employees/Index');
    }

    public function importCsv(ImportCSVEmployeeRequest $request): JsonResponse
    {
        $request->validated();

        $file = fopen($request->file->getRealPath(), 'r');
        $header = fgetcsv($file);

        $created = 0;
        $errors = [];

        while (($row = fgetcsv($file)) !== false) {
            [$internalId, $firstName, $lastName, $hasAccess] = $row;

            $validator = Validator::make([
                'internal_id' => $internalId,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ], [
                'internal_id' => 'required|unique:employees,internal_id',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'internal_id' => $internalId,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            Employee::create([
                'internal_id' => $internalId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'department_id' => $request->department_id,
                'has_access' => filter_var($hasAccess, FILTER_VALIDATE_BOOLEAN),
            ]);

            $created++;
        }

        fclose($file);

        return response()->json([
            'created' => $created,
            'errors' => $errors,
        ]);
    }
}
