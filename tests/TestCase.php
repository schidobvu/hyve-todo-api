<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public ?User $user = null;
    public string $token;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function login(?User $user = null): self
    {
        $this->user = $user ?? User::factory()->create([
            'name' => 'Sam',
            'email' => 'sam@gmail.com',
        ]);

        $this->token = JWTAuth::fromUser($this->user);

        $this->withHeaders([
            'Authorization' => sprintf('Bearer %s', $this->token),
            'Accept' => 'application/json',
        ]);

        $this->actingAs($this->user, 'api');

        return $this;
    }
}
