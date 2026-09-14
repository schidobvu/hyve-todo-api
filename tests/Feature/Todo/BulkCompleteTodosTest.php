<?php

namespace Tests\Feature\Todo;

use App\Jobs\BulkCompleteTodosJob;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BulkCompleteTodosTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_dispatch_bulk_complete_job(): void
    {
        Queue::fake();

        $this->login();

        $todos = Todo::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'completed' => false,
        ]);

        $todoIds = $todos->pluck('id')->toArray();

        $response = $this->postJson('/api/v1/todos/bulk-complete', [
            'todo_ids' => $todoIds,
        ]);

        $response->assertAccepted()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'job_id',
                    'status',
                ],
            ]);

        Queue::assertPushed(BulkCompleteTodosJob::class, function ($job) use ($todoIds) {
            return $job->userId === $this->user->id && $job->todoIds === $todoIds;
        });
    }
}
