<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;
class DeleteTodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(Todo $todo): JsonResponse
    {
        if ($todo->{'user_id'} !== auth()->id()) {
            return $this->respond()->unauthorized('Unauthorized action. You do not own this todo.')->json();
        }

        $this->todoService->deleteTodo($todo);

        return $this->respond()->ok()->json();
    }
}
