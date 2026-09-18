<?php

namespace Tests\Feature\Job;

use App\Models\JobStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_fetch_job_status(): void
    {
        $this->login();

        $jobStatus = JobStatus::factory()->create([
            'user_id' => $this->user->getKey(),
            'status' => 'completed',
            'result' => ['updated_count' => 3],
        ]);

        $response = $this->get("/api/v1/jobs/{$jobStatus->id}/status");

        $response->assertOk()
            ->assertJson([
                'job_status' => [
                    'id' => $jobStatus->id,
                    'type' => 'bulk_complete',
                    'status' => 'completed',
                    'result' => [
                        'updated_count' => 3,
                    ],
                ],
            ]);
    }
}
