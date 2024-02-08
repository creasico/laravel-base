<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\Auth\NewPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('creasi::auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $status = $request->fulfill();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __($status),
                'errors' => $status != Password::RESET_LINK_SENT ?: [
                    'email' => [__($status)],
                ],
            ]);
        }

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(NewPasswordRequest $request)
    {
        $status = $request->fulfill();

        if ($request->expectsJson()) {
            return \response()->json([
                'message' => __($status),
                'errors' => $status != Password::PASSWORD_RESET ?: [
                    'email' => [__($status)],
                ],
            ]);
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
