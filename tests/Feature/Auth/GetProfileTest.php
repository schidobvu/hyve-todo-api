<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class GetProfileTest extends TestCase
{

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $response = $this->login()->get('/api/v1/auth/me');

        $response->assertOk()->assertJsonPath('user_profile.email', $this->user->{'email'});
    }

}
