<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JobStatusRepositoryInterface;
use Illuminate\Http\JsonResponse;

class GetJobStatusController extends Controller
{
    public function __construct(
        private readonly JobStatusRepositoryInterface $jobStatusRepository
    )
    {
    }

    public function __invoke(string $uuid): JsonResponse
    {
        $jobStatus = $this->jobStatusRepository->findForUser($uuid, auth()->id());

        if (!$jobStatus) return $this->respond()->notFound()->message("Job status not found")->json();

        return $this->respond()
            ->ok($jobStatus)
            ->key('job_status')
            ->message('Job status retrieved successfully.')
            ->json();
    }
}
