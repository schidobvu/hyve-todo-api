<?php

namespace App\Services\Todo;

use App\Jobs\BulkCompleteTodosJob;
use App\Models\JobStatus;
use App\Models\Todo;
use App\Repositories\Contracts\TodoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TodoService
{
    public function __construct(
        protected TodoRepositoryInterface $todoRepository
    )
    {
    }

    public function getUserTodos(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->todoRepository->getFilteredUserTodos($userId, $perPage);
    }

    public function createTodo(int $userId, array $data): Todo
    {
        $data['user_id'] = $userId;

        return $this->todoRepository->create($data);
    }

    public function getTodoForUser(int $userId, int $todoId): ?Todo
    {
        return $this->todoRepository->findForUser($userId, $todoId);
    }

    public function updateTodo(Todo $todo, array $data): Todo
    {
        $this->todoRepository->update($todo, $data);

        return $todo->fresh();
    }

    public function deleteTodo(Todo $todo): bool
    {
        return $this->todoRepository->delete($todo);
    }

    public function bulkCompleteAsync(int $userId, array $todoIds): JobStatus
    {
        $jobStatus = JobStatus::create([
            'id' => (string)Str::uuid(),
            'user_id' => $userId,
            'type' => 'bulk_complete',
            'status' => 'pending',
            'payload' => ['todo_ids' => $todoIds],
        ]);

        BulkCompleteTodosJob::dispatch($jobStatus->id, $userId, $todoIds);

        return $jobStatus;
    }
}
