<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\BulkCompleteTodoRequest;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;

class BulkCompleteTodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    )
    {
    }

    public function __invoke(BulkCompleteTodoRequest $request): JsonResponse
    {
        $todoIds = $request->validated('todo_ids');

        $jobStatus = $this->todoService->bulkCompleteAsync(auth()->id(), $todoIds);

        return $this->respond()
            ->accepted([
                'job_id' => $jobStatus->{'id'},
                'status' => $jobStatus->{'status'},
                'status_url' => url("/api/v1/jobs/{$jobStatus->{'id'}}/status"),
            ])
            ->message('Bulk completion job has been queued successfully.')
            ->json();
    }
}
