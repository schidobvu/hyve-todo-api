<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Validation\ValidationException;
class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function login(array $credentials): array
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid username or password.'],
            ]);
        }

        return $this->respondWithToken($token);
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => auth('api')->user(),
        ];
    }
}
