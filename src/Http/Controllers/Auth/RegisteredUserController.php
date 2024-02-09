<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\Auth\RegistrationRequest;
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
        return view('creasi::auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(RegistrationRequest $request)
    {
        $user = $request->fulfill();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('auth.notices.verify-email'),
            ], 201);
        }

        Auth::login($user);

        return redirect(app('creasi.base.route_home'));
    }
}
