<?php

namespace Tests\Feature\Todo;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_todo(): void
    {
        $payload = [
            'title' => 'Buy Groceries',
            'description' => 'Milk, Eggs, Bread',
        ];

        $response = $this->login()->post('/api/v1/todos', $payload);

        $response->assertCreated()->assertJsonPath('todo.title', 'Buy Groceries');

        $this->assertDatabaseHas('todos', [
            'user_id' => $this->user->getKey(),
            'title' => 'Buy Groceries',
        ]);
    }

    public function test_create_todo_requires_title(): void
    {
        $response = $this->login()->post('/api/v1/todos', [
            'description' => 'Missing title',
        ]);

        $response->assertBadRequest()->assertJsonValidationErrors(['title']);
    }

    public function test_unauthenticated_user_cannot_create_todo(): void
    {
        $response = $this->postJson('/api/v1/todos', [
            'title' => 'Buy Groceries',
        ]);

        $response->assertUnauthorized();
    }
}
