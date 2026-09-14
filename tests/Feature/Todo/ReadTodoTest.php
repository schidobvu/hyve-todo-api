<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_only_their_own_todos(): void
    {
        $this->login();

        Todo::factory()->count(3)->create(['user_id' => $this->user->getKey()]);

        $otherUser = User::factory()->create();
        Todo::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $response = $this->get('/api/v1/todos');

        $response->assertOk()->assertJsonCount(3, 'todos.data');
    }

    public function test_user_can_view_single_todo(): void
    {
        $this->login();

        $todo = Todo::factory()->create(['user_id' => $this->user->getKey()]);

        $response = $this->get("/api/v1/todos/{$todo->getKey()}");

        $response->assertOk()->assertJsonPath('todo.id', $todo->getKey());
    }

    public function test_user_cannot_view_another_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $otherTodo = Todo::factory()->create(['user_id' => $otherUser->getKey()]);

        $response = $this->login()->get("/api/v1/todos/{$otherTodo->getKey()}");

        $response->assertForbidden();
    }
}
