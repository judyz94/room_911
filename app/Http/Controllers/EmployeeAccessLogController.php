<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeAccessLogResource;
use App\Models\AccessLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAccessLogController
{
    public function index(Request $request, Employee $employee): JsonResource
    {
        $logs = AccessLog::where('employee_id', $employee->id)
            ->when($request->from, fn ($q) => $q->whereDate('attempted_at', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->whereDate('attempted_at', '<=', $request->to))
            ->orderByDesc('attempted_at')
            ->get();

        return EmployeeAccessLogResource::collection($logs);
    }
}
