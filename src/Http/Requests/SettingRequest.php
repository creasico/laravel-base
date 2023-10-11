<?php

namespace Creasi\Base\Http\Requests;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            // .
        ];
    }
}
