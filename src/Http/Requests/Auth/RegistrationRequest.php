<?php

namespace Creasi\Base\Http\Requests\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'unique:users,name'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return mixed
     */
    public function fulfill(): Authenticatable
    {
        $model = config('creasi.base.user_model');
        $user = $model::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        event(new Registered($user));

        return $user;
    }
}
