<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\FileType;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property string $file_id
 * @property int $attachable_id
 * @property string $attachable_type
 * @property null|FileType $type
 * @property-read \Creasi\Base\Contracts\HasFileUploads $attachable
 */
class FileAttached extends MorphPivot
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['type', 'file_id'];

    protected $casts = [
        'attachable_id' => 'int',
        'type' => FileType::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachable()
    {
        return $this->morphTo('attachable');
    }
}
