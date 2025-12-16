<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Admin/Register', [
            'title' => 'Register Admin ROOM 911'
        ]);
    }

    public function store(RegisterUserRequest $request): RedirectResponse
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin_room_911',
        ]);

        return back()->with('success', 'Admin user created successfully.');
    }
}
