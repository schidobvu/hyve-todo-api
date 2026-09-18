<?php

namespace App\Repositories\Contracts;

use App\Models\JobStatus;

interface JobStatusRepositoryInterface
{
    public function findForUser(string $uuid, int $userId): ?JobStatus;

    public function createBulkCompleteJob(int $userId, array $todoIds): JobStatus;
}
