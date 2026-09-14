<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;

class ListTodosController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        $todos = $this->todoService->getUserTodos(auth()->id());

        return $this->respond()
            ->ok($todos)
            ->key('todos')
            ->message('Todos retrieved successfully.')
            ->json();
    }
}
