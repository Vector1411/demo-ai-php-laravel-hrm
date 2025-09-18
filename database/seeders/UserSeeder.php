<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $departments = \App\Models\Department::pluck('id', 'name');
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin@123'),
                'full_name' => 'Admin User',
                'role' => 'ADMIN',
                'department_id' => $departments['HR'] ?? null,
                'is_active' => true,
            ],
            [
                'username' => 'hr',
                'email' => 'hr@example.com',
                'password' => Hash::make('Hr@12345'),
                'full_name' => 'HR User',
                'role' => 'HR',
                'department_id' => $departments['HR'] ?? null,
                'is_active' => true,
            ],
            [
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('Manager@123'),
                'full_name' => 'Manager User',
                'role' => 'MANAGER',
                'department_id' => $departments['Sales'] ?? null,
                'is_active' => true,
            ],
            [
                'username' => 'employee',
                'email' => 'employee@example.com',
                'password' => Hash::make('Employee@123'),
                'full_name' => 'Employee User',
                'role' => 'EMPLOYEE',
                'department_id' => $departments['IT'] ?? null,
                'is_active' => true,
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
