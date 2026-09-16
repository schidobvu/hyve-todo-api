<?php

namespace Tests\Unit\Repositories;

use App\Models\Todo;
use App\Models\User;
use App\Repositories\Eloquent\EloquentTodoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentTodoRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentTodoRepository();
    }

    public function test_it_returns_only_todos_belonging_to_given_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Todo::factory()->count(3)->create(['user_id' => $userA->id]);
        Todo::factory()->count(2)->create(['user_id' => $userB->id]);

        $userATodos = $this->repository->getAllByUserId($userA->id);

        $this->assertCount(3, $userATodos);
        $this->assertTrue($userATodos->every(fn ($todo) => $todo->user_id === $userA->id));
    }

    public function test_it_bulk_updates_todo_statuses(): void
    {
        $user = User::factory()->create();
        $todos = Todo::factory()->count(3)->create([
            'user_id' => $user->id,
            'completed' => false,
        ]);

        $ids = $todos->pluck('id')->toArray();

        $updatedCount = $this->repository->bulkCompleteForUser($user->id, $ids);

        $this->assertEquals(3, $updatedCount);
        $this->assertDatabaseHas('todos', [
            'id' => $ids[0],
            'completed' => true,
        ]);
    }
}
