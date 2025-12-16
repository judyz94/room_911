<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@room911.test'],
            [
                'name' => 'Admin ROOM 911',
                'password' => Hash::make('12345678'),
                'role' => 'admin_room_911',
            ]
        );
    }
}
