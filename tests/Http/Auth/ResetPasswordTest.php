<?php

namespace Creasi\Tests\Http\Auth;

use Creasi\Tests\TestCase;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('auth')]
#[Group('reset-password')]
class ResetPasswordTest extends TestCase
{
    #[Test]
    public function user_can_request_new_password()
    {
        Notification::fake();

        Event::fake([
            PasswordReset::class,
        ]);

        $user = $this->user();

        $response = $this->postJson('auth/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notif) use ($user) {
            $url = \parse_url(\call_user_func(ResetPassword::$createUrlCallback, $user, $notif->token));

            $response = $this->getJson($url['path'].'?'.$url['query']);

            $response->assertOk()
                ->assertJsonPath('token', $notif->token);

            $response = $this->putJson('auth/reset-password', [
                'token' => $notif->token,
                'email' => $user->email,
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

            $response->assertOk();

            return true;
        });

        Event::assertDispatched(PasswordReset::class);

        $response->assertOk();

        return $user;
    }
}
