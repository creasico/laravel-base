<?php

namespace Creasi\Base\Http\Requests;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $key = $this->user()->profile->getKey();

        return [
            'name' => ['required', 'string', 'max:150'],
            'alias' => ['nullable', 'string', 'max:50', Rule::unique(Person::class, 'alias')->ignore($key)],
            'email' => ['required', 'email', 'max:150', Rule::unique(Person::class, 'email')->ignore($key)],
            'phone' => ['nullable', 'numeric', 'max_digits:20'],
            'prefix' => ['nullable', 'string', 'max:20'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill()
    {
        /** @var \App\Models\User */
        $user = $this->user();

        $user->profile()->update($this->validated());

        $user->load('profile');

        return $user;
    }
}
