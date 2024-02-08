<?php

namespace Creasi\Base\Http\Controllers\Auth;

use Creasi\Base\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('creasi::auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = [
            'email' => $request->user()->email,
            'password' => $request->password,
        ];

        if (! Auth::guard('web')->validate($data)) {
            throw ValidationException::withMessages([
                'password' => __('creasico::auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(app('creasi.base.route_home'));
    }
}
