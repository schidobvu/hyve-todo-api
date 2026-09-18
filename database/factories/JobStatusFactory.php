<?php

namespace Database\Factories;

use App\Models\JobStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobStatusFactory extends Factory
{
    protected $model = JobStatus::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(),
            'type' => 'bulk_complete',
            'status' => 'completed',
            'payload' => ['todo_ids' => [1, 2, 3]],
            'result' => ['updated_count' => 3],
        ];
    }
}
