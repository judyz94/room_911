<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\Employee;
use Carbon\Carbon;
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

        if (!$employee) {
            AccessLog::create([
                'internal_id' => $request->internal_id,
                'employee_id' => null,
                'access_granted' => false,
                'attempted_at' => now(),
            ]);

            return response()->json([
                'granted' => false,
                'status' => 'not_found',
                'message' => 'Employee not found. Access denied.',
                'timestamp' => now(),
            ], 200);
        }

        $granted = (bool) $employee->has_access;

        AccessLog::create([
            'internal_id' => $employee->internal_id,
            'employee_id' => $employee->id,
            'access_granted' => $granted,
            'attempted_at' => now(),
        ]);

        $now = Carbon::now()->format('Y-m-d H:i:s');

        return response()->json([
            'granted' => $granted,
            'status' => $granted ? 'granted' : 'denied',
            'message' => $granted
                ? "Access granted.\nWelcome {$employee->full_name}.\n{$now}"
                : "Access denied.\nYou do not have permission.\n{$now}",
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->full_name,
            ],
            'timestamp' => now(),
        ], 200);
    }

    public function form(): Response
    {
        return Inertia::render('Employees/Access');
    }
}
