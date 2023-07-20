<?php

namespace Creasi\Base\Http\Requests\FileUpload;

use Creasi\Base\Contracts\HasFileUploads;
use Creasi\Base\Http\Requests\FormRequest;
use Creasi\Base\Models\Enums\FileUploadType;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'name' => ['required', 'string'],
            'type' => ['required', Rule::enum(FileUploadType::class)],
            'upload' => ['nullable', 'file'],
        ];
    }

    public function storeFor(HasFileUploads $entity)
    {
        /** @var FileUploadType */
        $type = $this->enum('type', FileUploadType::class);

        return $entity->storeFile(
            $type,
            $this->file('upload'),
            $this->name,
            $this->title,
        );
    }
}
