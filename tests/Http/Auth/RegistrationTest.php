<?php

namespace Creasi\Tests\Http\Auth;

use Creasi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('auth')]
#[Group('registration')]
class RegistrationTest extends TestCase
{
    #[Test]
    public function visitor_can_register_a_new_user_account(): void
    {
        Event::fake([
            Registered::class,
        ]);

        $response = $this->postJson('auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Event::assertDispatched(Registered::class);

        $response->assertCreated();
    }
}
