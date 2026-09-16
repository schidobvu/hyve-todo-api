<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        $payload = [
            'name' => 'Sam Chidobvu',
            'email' => 'sam@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertCreated()
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
                'message' => 'User registered successfully.',
                'data' => [
                    'token_type' => 'bearer',
                    'user' => [
                        'name' => 'Sam Chidobvu',
                        'email' => 'sam@gmail.com',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'sam@gmail.com',
        ]);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create([
            'email' => 'sam@gmail.com',
        ]);

        $payload = [
            'name' => 'Sam Chidobvu',
            'email' => 'sam@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertBadRequest()->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_if_passwords_do_not_match(): void
    {
        $payload = [
            'name' => 'Sam Chidobvu',
            'email' => 'sam@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'mismatched_password',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertBadRequest()->assertJsonValidationErrors(['password']);
    }
}
