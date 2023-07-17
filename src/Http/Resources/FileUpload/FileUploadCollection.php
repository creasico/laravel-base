<?php

namespace Creasi\Base\Http\Resources\FileUpload;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileUploadCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
