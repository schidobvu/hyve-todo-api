<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    public function test_it_returns_jwt_token_on_successful_authentication(): void
    {
        User::factory()->create([
            'email' => 'sam@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $result = $this->authService->login([
            'email' => 'sam@gmail.com',
            'password' => '12345678',
        ]);

        $this->assertArrayHasKey('access_token', $result);
        $this->assertIsString($result['access_token']);
    }

    public function test_it_throws_validation_exception_on_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'sam@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->expectException(ValidationException::class);

        $this->authService->login([
            'email' => 'sam@gmail.com',
            'password' => '123',
        ]);
    }
}
