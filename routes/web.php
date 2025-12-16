<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeAccessLogController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AuthController::class, 'form']);

Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/room-911/access', [AccessController::class, 'form']);
Route::post('/room-911/access', [AccessController::class, 'attempt']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/user/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/user/register', [RegisterController::class, 'store']);

    Route::get('/departments', [DepartmentController::class, 'view'])->name('departments');

    Route::get('/employees', fn () => Inertia::render('Employees/Index'));
    Route::get('employees/{employee}/access-logs', [EmployeeAccessLogController::class, 'index']);

    Route::resource('api/departments', DepartmentController::class)->except(['create', 'edit', 'show']);
    Route::resource('api/employees', EmployeeController::class)->except(['create', 'edit', 'show']);
});

