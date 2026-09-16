<?php

namespace App\Http\Requests\Auth;

use Bluecloud\ResponseBuilder\Requests\BaseFormRequest;

class RegisterRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

    }
}
