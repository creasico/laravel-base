<?php

namespace Creasi\Base\Http\Requests\File;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\File;
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

    public function fulfill(File $file)
    {
        return $file->update($this->validated());
    }
}
