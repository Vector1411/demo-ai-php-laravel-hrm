<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = \App\Models\Department::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('DEPT##'),
            'name' => $this->faker->company,
            'parent_id' => null, // sẽ cập nhật sau khi tạo nếu cần
            'head_id' => null, // sẽ cập nhật sau khi có user
        ];
    }
}
