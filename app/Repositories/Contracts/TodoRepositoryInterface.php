<?php

namespace App\Repositories\Contracts;

use App\Models\Todo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TodoRepositoryInterface
{
    public function getFilteredUserTodos(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getByUserIdPaginated(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getAllByUserId(int $userId): Collection;

    public function findForUser(int $userId, int $todoId): ?Todo;

    public function create(array $data): Todo;

    public function update(Todo $todo, array $data): bool;

    public function delete(Todo $todo): bool;

    public function bulkCompleteForUser(int $userId, array $todoIds): int;
}
