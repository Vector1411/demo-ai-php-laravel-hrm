<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;

class HRMSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Department::factory()->count(5)->create();
        \App\Models\User::factory()->count(50)->create();
    }
}
