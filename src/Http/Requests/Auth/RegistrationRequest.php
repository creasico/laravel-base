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
            'name' => ['required', 'string', 'min:2', 'max:150'],
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
        $user = $model::create($this->only('name', 'email', 'password'));

        event(new Registered($user));

        return $user;
    }
}
