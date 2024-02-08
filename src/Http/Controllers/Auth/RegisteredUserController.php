<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\Auth\RegistrationRequest;

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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegistrationRequest $request)
    {
        $request->fulfill();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('auth.notices.verify-email'),
            ], 201);
        }

        return redirect(app('creasi.base.route_home'));
    }
}
