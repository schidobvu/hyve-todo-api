<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentUserRepository();
    }

    public function test_it_can_find_user_by_id(): void
    {
        $user = User::factory()->create();

        $foundUser = $this->repository->findById($user->id);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_it_returns_null_when_user_not_found_by_id(): void
    {
        $foundUser = $this->repository->findById(999);

        $this->assertNull($foundUser);
    }

    public function test_it_can_find_user_by_email(): void
    {
        $user = User::factory()->create(['email' => 'sam@gmail.com']);

        $foundUser = $this->repository->findByEmail('sam@gmail.com');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_it_can_create_a_user(): void
    {
        $userData = [
            'name' => 'Sam Chidobvu',
            'email' => 'sam@gmail.com',
            'password' => bcrypt('12345678'),
        ];

        $user = $this->repository->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'email' => 'sam@gmail.com',
        ]);
    }

    public function test_it_can_update_a_user(): void
    {
        $user = User::factory()->create(['name' => 'Original Name']);

        $updated = $this->repository->update($user, ['name' => 'Updated Name']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_it_can_delete_a_user(): void
    {
        $user = User::factory()->create();

        $deleted = $this->repository->delete($user);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_it_can_get_all_users(): void
    {
        User::factory()->count(3)->create();

        $users = $this->repository->getAll();

        $this->assertCount(3, $users);
    }
}
