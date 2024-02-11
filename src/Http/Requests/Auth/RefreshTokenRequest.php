<?php

namespace Creasi\Base\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @method \Illuminate\Foundation\Auth\User|\Creasi\Base\Contracts\HasCredentialTokens|null user()
 */
class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->tokenCan('refresh-auth') ?: false;
    }
}
