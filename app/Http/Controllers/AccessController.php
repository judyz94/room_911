<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccessController extends Controller
{
    public function attempt(Request $request): JsonResponse
    {
        $request->validate([
            'internal_id' => 'required'
        ]);

        $employee = Employee::where('internal_id', $request->internal_id)->first();

        $granted = $employee && $employee->has_access;

        AccessLog::create([
            'internal_id' => $request->internal_id,
            'employee_id' => $employee?->id,
            'access_granted' => $granted,
            'attempted_at' => now(),
        ]);

        return response()->json([
            'granted' => $granted,
            'message' => $granted
                ? 'Access granted'
                : 'Access denied',
        ]);
    }

    public function form(): Response
    {
        return Inertia::render('Employees/Access');
    }
}
