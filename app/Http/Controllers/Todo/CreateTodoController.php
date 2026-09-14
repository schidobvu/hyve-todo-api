<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\CreateTodoRequest;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;

class CreateTodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(CreateTodoRequest $request): JsonResponse
    {
        $todo = $this->todoService->createTodo(auth()->id(), $request->validated());

        return $this->respond()
            ->created($todo->toArray())
            ->key('todo')
            ->message('Todo created successfully.')
            ->json();
    }
}
