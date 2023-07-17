<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
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
