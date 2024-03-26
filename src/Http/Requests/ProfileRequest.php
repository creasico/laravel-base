<?php

namespace Creasi\Base\Http\Requests;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Enums\Education;
use Creasi\Base\Enums\TaxStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string'],
            'nickname' => ['nullable', 'string'],
            'phone' => ['required', 'string'],
            'summary' => ['nullable', 'string', 'max:200'],
            'prefix' => ['nullable', 'string'],
            'suffix' => ['nullable', 'string'],
            'education' => ['nullable', Rule::enum(Education::class)],
            'tax_status' => ['nullable', Rule::enum(TaxStatus::class)],
            'tax_id' => ['nullable', 'string'],
        ];
    }

    public function fulfill()
    {
        $user = $this->user();
        $data = $this->validated();

        $user->identity()->update([
            'name' => $data['fullname'],
            'alias' => $data['nickname'],
            'phone' => $data['phone'],
            'summary' => $data['summary'],
            'prefix' => $data['prefix'],
            'suffix' => $data['suffix'],
            'education' => $this->enum('education', Education::class),
            'tax_status' => $this->enum('tax_status', TaxStatus::class),
            'tax_id' => $data['tax_id'],
        ]);

        $user->load('identity');

        return $user;
    }
}
