<?php

namespace App\Http\Requests\Todo;

use Bluecloud\ResponseBuilder\Requests\BaseFormRequest;

class BulkCompleteTodoRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'todo_ids' => ['required', 'array', 'min:1'],
            'todo_ids.*' => ['required', 'integer', 'exists:todos,id'],
        ];
    }
}
