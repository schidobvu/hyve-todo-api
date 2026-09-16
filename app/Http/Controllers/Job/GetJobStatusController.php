<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\JobStatus;
use Illuminate\Http\JsonResponse;
class GetJobStatusController extends Controller
{
    public function __invoke(string $uuid): JsonResponse
    {
        $jobStatus = JobStatus::findByUuid($uuid);
        return $this->respond()
            ->ok($jobStatus)
            ->key('job_status')
            ->message('Job status retrieved successfully.')
            ->json();
    }
}
