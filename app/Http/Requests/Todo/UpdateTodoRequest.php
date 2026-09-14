<?php

namespace App\Http\Requests\Todo;

use Bluecloud\ResponseBuilder\Requests\BaseFormRequest;

class UpdateTodoRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
        ];
    }
}
