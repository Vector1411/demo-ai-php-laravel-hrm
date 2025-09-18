<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $response = $this->postJson('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token']);
    }

    public function test_login_lockout()
    {
        $user = User::factory()->create(['password' => bcrypt('password'), 'locked' => true]);
        $response = $this->postJson('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertStatus(423); // Locked
    }
}
