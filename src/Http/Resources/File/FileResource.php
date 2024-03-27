<?php

namespace Creasi\Base\Http\Resources\File;

use Creasi\Base\Database\Models\FileAttached;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \Creasi\Base\Database\Models\File $resource
 */
class FileResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->additional([
            'meta' => [],
            'links' => [],
        ]);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->resource->getKeyName() => $this->resource->getKey(),
            'title' => $this->resource->title,
            'name' => $this->resource->name,
            'path' => $this->resource->path,
            'disk' => $this->resource->disk,
            'summary' => $this->resource->summary,
            'url' => $this->resource->url,
            'attaches' => $this->resource->attaches->map(fn (FileAttached $a) => [
                $a->attachable->getKeyName() => $a->attachable->getKey(),
                $a->attachable->getAttachableKeyName() => $a->attachable->getAttachableKey(),
                'type' => $a->type?->toArray(),
            ]),
            'is_internal' => $this->resource->is_internal,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
