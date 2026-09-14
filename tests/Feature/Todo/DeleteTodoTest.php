<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_todo(): void
    {
        $this->login();
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/api/v1/todos/{$todo->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $otherTodo = Todo::factory()->create(['user_id' => $otherUser->getKey()]);

        $response = $this->login()->delete("/api/v1/todos/{$otherTodo->getKey()}");

        $response->assertForbidden();

        $this->assertDatabaseHas('todos', [
            'id' => $otherTodo->getKey(),
        ]);
    }
}
