<?php

namespace Creasi\Base\Http\Requests\FileUpload;

use Creasi\Base\Contracts\HasFileUploads;
use Creasi\Base\Http\Requests\FormRequest;
use Creasi\Base\Models\Enums\FileUploadType;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            // .
        ];
    }

    public function storeFor(HasFileUploads $entity)
    {
        return $entity->storeFile(
            FileUploadType::Document,
            $this->file('upload'),
            $this->input('name'),
            $this->input('title'),
            $this->input('summary'),
        );
    }
}
