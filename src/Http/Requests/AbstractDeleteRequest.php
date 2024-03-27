<?php

namespace Creasi\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractDeleteRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'force' => ['nullable', 'boolean'],
        ];
    }
}
