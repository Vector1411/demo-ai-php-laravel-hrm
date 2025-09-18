<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_crud_happy_path()
    {
        $user = User::factory()->create(['role' => 'ADMIN']);
        $token = auth()->login($user);

        $headers = ['Authorization' => "Bearer $token"];

        // Create
        $response = $this->postJson('/api/users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'full_name' => 'New User',
            'role' => 'EMPLOYEE',
        ], $headers);
        $response->assertStatus(201);

        // Read
        $id = $response->json('id');
        $response = $this->getJson("/api/users/$id", $headers);
        $response->assertStatus(200);

        // Update
        $response = $this->putJson("/api/users/$id", [
            'full_name' => 'Updated User',
        ], $headers);
        $response->assertStatus(200);

        // Delete
        $response = $this->deleteJson("/api/users/$id", [], $headers);
        $response->assertStatus(204);
    }
}
