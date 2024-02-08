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
            ? response()->json(['status' => 'ok'])
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
            ? response()->json(['status' => 'verified'])
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
            ? response()->json(['status' => 'verification-link-sent'])
            : back()->with('status', 'verification-link-sent');
    }
}
