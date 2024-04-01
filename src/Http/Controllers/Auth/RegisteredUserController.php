<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\Auth\RegistrationRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('creasi::pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(RegistrationRequest $request)
    {
        $user = $request->fulfill();

        $message = $user instanceof MustVerifyEmail
            ? __('creasi::auth.registered-needs-verify')
            : __('creasi::auth.registered-no-verify');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 201);
        }

        Auth::login($user);

        return redirect(app('creasi.base.route_home'))->with('message', $message);
    }
}
