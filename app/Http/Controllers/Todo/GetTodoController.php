<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;

class GetTodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(Todo $todo): JsonResponse
    {
        $todoData = $this->todoService->getTodoForUser(auth()->id(), $todo->getKey());

        return $this->respond()
            ->ok($todoData)
            ->key('todo')
            ->message('Todo retrieved successfully.')
            ->json();
    }
}
