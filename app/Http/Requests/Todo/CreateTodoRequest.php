<?php

namespace App\Http\Requests\Todo;

use Bluecloud\ResponseBuilder\Requests\BaseFormRequest;

class CreateTodoRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'completed' => ['nullable', 'boolean'],
        ];
    }
}
