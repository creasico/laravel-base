<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Contracts\HasCredentialTokens;
use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\Auth\LoginRequest;
use Creasi\Base\Http\Requests\Auth\RefreshTokenRequest;
use Creasi\Base\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function verify(Request $request)
    {
        if (! $request->expectsJson()) {
            return \redirect()->intended(app('creasi.base.route_home'));
        }

        return UserResource::make($request->user());
    }

    public function create()
    {
        return view('creasi::pages.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if ($request->expectsJson()) {
            $user = $request->user();
            $response = UserResource::make($user);

            if ($user instanceof HasCredentialTokens) {
                $response->additional(['auth' => $user->createCredentialTokens()]);
            }

            return $response->toResponse($request)->setStatusCode(201);
        }

        $request->session()->regenerate();

        return redirect()->intended(app('creasi.base.route_home'));
    }

    /**
     * Handle an incoming refresh credential token request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        if (! $request->expectsJson()) {
            return \redirect()->intended(app('creasi.base.route_home'));
        }

        $user = $request->user();
        $response = UserResource::make($user);

        if ($user instanceof HasCredentialTokens) {
            $response->additional(['auth' => $user->refreshCredentialTokens($request)]);
        }

        return $response->toResponse($request)->setStatusCode(201);
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if ($request->expectsJson()) {
            $user = $request->user();

            if ($user instanceof HasCredentialTokens) {
                $user->destroyCredential($request);
            }

            return response()->noContent();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return \redirect()->intended(app('creasi.base.route_home'));
    }
}
