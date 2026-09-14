<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    )
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $tokenData = $this->authService->login($credentials);

        return $this->respond()->ok($tokenData)->json();
    }
}
