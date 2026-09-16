<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $tokenData = $this->authService->register($request->validated());

        return $this->respond()
            ->created($tokenData)
            ->message('User registered successfully.')
            ->json();
    }
}
