<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     *
     * @return mixed
     */
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(app('creasi.base.route_home'));
        }

        return $request->expectsJson()
            ? response()->json(['message' => 'ok'])
            : redirect()->intended(app('creasi.base.route_home'));
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return $request->expectsJson()
            ? response()->json(['message' => __('creasico::auth.email-verified')])
            : redirect()->intended(app('creasi.base.route_home').'?verified=1');
    }

    /**
     * Send a new email verification notification.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(app('creasi.base.route_home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->expectsJson()
            ? response()->json(['message' => __('creasico::auth.email-verification-sent')])
            : back()->with('message', __('creasico::auth.email-verification-sent'));
    }
}
