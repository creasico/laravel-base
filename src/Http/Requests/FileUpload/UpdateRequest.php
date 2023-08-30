<?php

namespace Creasi\Base\Http\Requests\FileUpload;

use Creasi\Base\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'name' => ['required', 'string'],
            'path' => ['required', 'string'],
            'upload' => ['nullable', 'file'],
        ];
    }
}
