<?php

namespace App\Repositories\Eloquent;

use App\Models\Todo;
use App\Repositories\Contracts\TodoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentTodoRepository implements TodoRepositoryInterface
{
    public function getFilteredUserTodos(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return QueryBuilder::for(Todo::where('user_id', $userId))
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('completed'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::scope('is_pending', 'pending'),
                AllowedFilter::scope('is_completed', 'completed'),
            ])
            ->allowedSorts(['title', 'completed', 'created_at', 'updated_at'])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }

    public function getByUserIdPaginated(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Todo::where('user_id', $userId)->latest()->paginate($perPage);
    }

    public function getAllByUserId(int $userId): Collection
    {
        return Todo::where('user_id', $userId)->latest()->get();
    }

    public function findForUser(int $userId, int $todoId): ?Todo
    {
        return Todo::where('user_id', $userId)->where('id', $todoId)->first();
    }

    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    public function update(Todo $todo, array $data): bool
    {
        return $todo->update($data);
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

    public function bulkCompleteForUser(int $userId, array $todoIds): int
    {
        return Todo::where('user_id', $userId)
            ->whereIn('id', $todoIds)
            ->update(['completed' => true]);
    }
}
