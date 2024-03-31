<?php

namespace Creasi\Base\Providers;

use Creasi\Base\Database\Models;
use Creasi\Base\Database\Models\Contracts;
use Creasi\Base\Listeners\RegisterUserDevice;
use Creasi\Base\Policies;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Sanctum\Sanctum;

class RouteServiceProvider extends IlluminateServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Contracts\Company::class => Policies\OrganizationPolicy::class,
        Contracts\Personnel::class => Policies\PersonPolicy::class,
        // Contracts\Stakeholder::class => Policies\StakeholderPolicy::class,
        Models\Address::class => Policies\AddressPolicy::class,
        Models\File::class => Policies\FilePolicy::class,
    ];

    public function boot(): void
    {
        // Register the components.

        $this->defineRoutes();
    }

    public function register(): void
    {
        // Register the components.

        $this->booting(function (): void {
            Event::listen(Login::class, RegisterUserDevice::class);

            $this->registerPolicies();
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    protected function defineRoutes(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return \route('base.password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
        });

        VerifyEmail::createUrlUsing(function ($user) {
            $expiration = \now()->addMinutes(\config('auth.verification.expire', 60));

            return URL::temporarySignedRoute('base.verification.verify', $expiration, [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]);
        });

        // Customize the way sanctum retrieves the access token.
        Sanctum::getAccessTokenFromRequestUsing(function (Request $request): ?string {
            // We'll check for the `Authorization` header first.
            if ($token = $request->bearerToken()) {
                return $token;
            }

            // If the header is not present, we'll check for the `api_token` query string.
            return $request->isMethodCacheable() ? $request->query('api_token') : null;
        });

        Authenticate::redirectUsing(fn () => \route('base.login'));

        Route::bind('company', fn () => app(Contracts\Company::class));
        Route::bind('personnel', fn () => app(Contracts\Personnel::class));
        Route::bind('stakeholder', fn () => app(Contracts\Stakeholder::class));

        if (app()->routesAreCached() || config('creasi.base.routes_enable') === false) {
            return;
        }

        Route::name('base.')->group(function (): void {
            $prefix = config('creasi.base.routes_prefix', 'base');
            $libPath = \realpath(__DIR__.'/../../');

            Route::prefix('auth')->group($libPath.'/routes/auth.php');

            Route::prefix($prefix)->group($libPath.'/routes/base.php');
        });
    }
}
