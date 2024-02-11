<?php

namespace Creasi\Tests\Http\Auth;

use Creasi\Tests\TestCase;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('auth')]
#[Group('registration')]
class EmailVerificationTest extends TestCase
{
    #[Test]
    public function user_can_request_to_verify_email_address(): void
    {
        Notification::fake();

        Event::fake([
            Verified::class,
        ]);

        Sanctum::actingAs($user = $this->user(['email_verified_at' => null]), ['*']);

        $response = $this->postJson('auth/email/verification-send');

        Notification::assertSentTo($user, VerifyEmail::class, function (VerifyEmail $notif) use ($user) {
            $url = \parse_url(\call_user_func(VerifyEmail::$createUrlCallback, $user));

            $response = $this->getJson($url['path'].'?'.$url['query']);

            $response->assertOk();

            return true;
        });

        Event::assertDispatched(Verified::class);

        $response->assertOk();
    }
}
