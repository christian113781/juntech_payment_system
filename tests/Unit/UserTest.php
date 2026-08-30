<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_generates_initials_from_name(): void
    {
        $user = new User([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'secret',
        ]);

        $this->assertSame('JC', $user->initials());
    }
}
