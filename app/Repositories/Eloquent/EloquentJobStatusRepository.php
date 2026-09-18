<?php

namespace App\Repositories\Eloquent;

use App\Models\JobStatus;
use App\Repositories\Contracts\JobStatusRepositoryInterface;
use Illuminate\Support\Str;


class EloquentJobStatusRepository implements JobStatusRepositoryInterface
{
    public function findForUser(string $uuid, int $userId): ?JobStatus
    {
        return JobStatus::where('id', $uuid)->where('user_id', $userId)->first();
    }

    public function createBulkCompleteJob(int $userId, array $todoIds): JobStatus
    {
        return JobStatus::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'type' => 'bulk_complete',
            'status' => 'pending',
            'payload' => ['todo_ids' => $todoIds],
        ]);
    }
}
