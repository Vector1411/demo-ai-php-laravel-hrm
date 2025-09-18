<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Department;

class DepartmentDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_department_with_users_returns_409()
    {
        $department = Department::factory()->create();
        User::factory()->create(['department_id' => $department->id]);
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $token = auth()->login($admin);

        $response = $this->deleteJson("/api/departments/{$department->id}", [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(409);
    }
}
