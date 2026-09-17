<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GetProfileController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return $this->respond()
            ->ok(auth()->user()->toArray())
            ->key("user_profile")
            ->message("User profile retrieved successfully.")
            ->json();
    }
}
