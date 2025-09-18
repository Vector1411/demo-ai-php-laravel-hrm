<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'HR', 'parent_id' => null, 'head_id' => null],
            ['name' => 'IT', 'parent_id' => null, 'head_id' => null],
            ['name' => 'Sales', 'parent_id' => null, 'head_id' => null],
        ];
        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
