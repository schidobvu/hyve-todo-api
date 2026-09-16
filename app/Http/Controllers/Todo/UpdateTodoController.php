<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;
class UpdateTodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(UpdateTodoRequest $request, Todo $todo): JsonResponse
    {
        $updatedTodo = $this->todoService->updateTodo($todo, $request->validated());

        return $this->respond()
            ->ok($updatedTodo)
            ->key('todo')
            ->message('Todo updated successfully.')
            ->json();
    }
}
