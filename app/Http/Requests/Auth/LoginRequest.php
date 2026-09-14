<?php

namespace App\Http\Requests\Auth;

use Bluecloud\ResponseBuilder\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
