<?php

namespace Creasi\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractIndexRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'page' => ['nullable', 'numeric'],
        ];
    }
}
