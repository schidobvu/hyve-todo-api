<?php

namespace App\Jobs;

use App\Models\JobStatus;
use App\Repositories\Contracts\TodoRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class BulkCompleteTodosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $jobStatusId,
        public int $userId,
        public array $todoIds
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(TodoRepositoryInterface $todoRepository): void
    {
        $jobStatus = JobStatus::find($this->jobStatusId);

        if (!$jobStatus) {
            return;
        }

        $jobStatus->update(['status' => 'processing']);

        try {
            $updatedCount = $todoRepository->bulkCompleteForUser($this->userId, $this->todoIds);

            $jobStatus->update([
                'status' => 'completed',
                'result' => [
                    'updated_count' => $updatedCount,
                ],
            ]);
        } catch (Throwable $e) {
            $jobStatus->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
