<?php

namespace Creasi\Base\Http\Requests\FileUpload;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest implements FormRequestContract
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
