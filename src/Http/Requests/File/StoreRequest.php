<?php

namespace Creasi\Base\Http\Requests\File;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\HasFiles;
use Creasi\Base\Enums\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'name' => ['required', 'string'],
            'type' => ['required', Rule::enum(FileType::class)],
            'upload' => ['nullable', 'file'],
        ];
    }

    public function fulfill(HasFiles $entity)
    {
        $file = $entity->storeFile(
            $this->enum('type', FileType::class),
            $this->file('upload'),
            $this->input('name'),
            $this->input('title'),
        );

        return $file;
    }
}
