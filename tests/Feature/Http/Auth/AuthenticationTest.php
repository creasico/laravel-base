<?php

namespace Creasi\Tests\Feature\Http\Auth;

use Creasi\Base\Events\CredentialTokenCreated;
use Creasi\Base\Events\CredentialTokenDestroyed;
use Creasi\Base\Events\CredentialTokenRefreshed;
use Creasi\Base\Events\UserDeviceRegistered;
use Creasi\Tests\TestCase;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('auth')]
#[Group('credentials')]
class AuthenticationTest extends TestCase
{
    #[Test]
    public function user_cannot_login_with_wrong_password(): void
    {
        $user = $this->user();

        $response = $this->postJson('auth/login', [
            'credential' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable();
    }

    #[Test]
    public function user_get_locked_out_when_attempts_login_too_many_times(): void
    {
        Event::fake([
            Lockout::class,
        ]);

        $user = $this->user();

        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('auth/login', [
                'credential' => $user->email,
                'password' => 'wrong-password',
            ]);

            $response->assertUnprocessable();
        }

        Event::assertDispatched(Lockout::class);
    }

    #[Test]
    public function user_can_send_login_request(): void
    {
        Event::fake([
            CredentialTokenCreated::class,
            UserDeviceRegistered::class,
        ]);

        $user = $this->user();

        $response = $this->postJson('auth/login', [
            'credential' => $user->email,
            'password' => 'password',
        ]);

        Event::assertDispatched(CredentialTokenCreated::class);
        Event::assertNotDispatched(UserDeviceRegistered::class);

        $response->assertCreated();
    }

    #[Test]
    public function user_can_send_login_request_and_register_new_device(): void
    {
        Event::fake([
            CredentialTokenCreated::class,
            UserDeviceRegistered::class,
        ]);

        $user = $this->user();
        $token = Str::random();

        $response = $this->postJson('auth/login', [
            'credential' => $user->email,
            'password' => 'password',
            'device_token' => $token,
        ]);

        Event::assertDispatched(CredentialTokenCreated::class);
        Event::assertDispatched(UserDeviceRegistered::class);

        $response->assertCreated();
    }

    #[Test]
    public function should_send_refresh_token_request(): void
    {
        Event::fake([
            CredentialTokenRefreshed::class,
        ]);

        Sanctum::actingAs($user = $this->user(), ['refresh-auth']);

        $auth = $user->createCredentialTokens();

        $response = $this->postJson('auth/refresh', headers: [
            'Authorization' => 'Bearer '.$auth['refresh_token'],
        ]);

        Event::assertDispatched(CredentialTokenRefreshed::class);

        $response->assertCreated()
            ->assertJsonPath('auth.refresh_token', $auth['refresh_token']);
    }

    #[Test]
    public function should_send_check_request(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('auth');

        $response->assertOk();
    }

    #[Test]
    public function should_send_logout_request(): void
    {
        Event::fake([
            CredentialTokenDestroyed::class,
        ]);

        Sanctum::actingAs($user = $this->user(), ['*']);

        $auth = $user->createCredentialTokens();

        $response = $this->deleteJson('auth', headers: [
            'Authorization' => 'Bearer '.$auth['access_token'],
        ]);

        Event::assertDispatched(CredentialTokenDestroyed::class);

        $response->assertNoContent();
    }
}
