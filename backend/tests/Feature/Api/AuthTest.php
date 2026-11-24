<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_and_get_a_token(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->getJson('/api/v1/employees')->assertUnauthorized();
    }
}
