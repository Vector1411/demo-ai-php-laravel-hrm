<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'full_name' => $this->faker->name,
            'role' => $this->faker->randomElement(['ADMIN', 'HR', 'MANAGER', 'EMPLOYEE']),
            'department_id' => Department::inRandomOrder()->first()?->id,
            'is_active' => true,
        ];
    }
}
