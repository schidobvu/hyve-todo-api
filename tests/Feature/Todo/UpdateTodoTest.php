<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_todo(): void
    {
        $this->login();

        $todo = Todo::factory()->create([
            'user_id' => $this->user->getKey(),
            'title' => 'Old Title',
            'completed' => false,
        ]);

        $response = $this->put("/api/v1/todos/{$todo->id}", [
            'title' => 'New Title',
            'completed' => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('todo.title', 'New Title')
            ->assertJsonPath('todo.completed', true);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->getKey(),
            'title' => 'New Title',
            'completed' => true,
        ]);
    }

    public function test_user_cannot_update_another_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $otherTodo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->login()->put("/api/v1/todos/{$otherTodo->id}", [
            'title' => 'Title',
        ]);

        $response->assertForbidden();
    }
}
