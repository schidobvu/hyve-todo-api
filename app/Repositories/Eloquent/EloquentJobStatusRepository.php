<?php

namespace App\Repositories\Eloquent;

use App\Models\JobStatus;
use App\Repositories\Contracts\JobStatusRepositoryInterface;

class EloquentJobStatusRepository implements JobStatusRepositoryInterface
{
    public function findForUser(string $uuid, int $userId): ?JobStatus
    {
        return JobStatus::where('id', $uuid)->where('user_id', $userId)->first();
    }
}
