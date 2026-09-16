<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodoPolicy
{
    public function view(User $user, Todo $todo): Response
    {
        return $this->ownsTodo($user, $todo);
    }

    public function update(User $user, Todo $todo): Response
    {
        return $this->ownsTodo($user, $todo);
    }

    public function delete(User $user, Todo $todo): Response
    {
        return $this->ownsTodo($user, $todo);
    }

    private function ownsTodo(User $user, Todo $todo): Response
    {
        return $user->{'id'} === $todo->{'user_id'}
            ? Response::allow()
            : Response::deny('Unauthorized action. You do not own this todo.');
    }
}
