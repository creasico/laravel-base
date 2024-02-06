<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\FileUploadType;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property string $file_upload_id
 * @property int $attachable_id
 * @property string $attachable_type
 * @property null|FileUploadType $type
 * @property-read \Creasi\Base\Contracts\HasFileUploads $attachable
 */
class FileAttached extends MorphPivot
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['type', 'file_upload_id'];

    protected $casts = [
        'attachable_id' => 'int',
        'type' => FileUploadType::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachable()
    {
        return $this->morphTo('attachable');
    }
}
