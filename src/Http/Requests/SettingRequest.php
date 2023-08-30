<?php

namespace Creasi\Base\Http\Requests;

class SettingRequest extends FormRequest
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
