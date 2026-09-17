<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'sam@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/api/v1/auth/login', [
            'email' => 'sam@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ])
            ->assertJson([
                'data' => [
                    'token_type' => 'bearer',
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                    ],
                ],
            ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'sam@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'sam@gmail.com',
            'password' => '123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/api/v1/auth/login', []);

        $response->assertBadRequest()
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
