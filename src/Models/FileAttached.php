<?php

namespace Creasi\Base\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property int $file_upload_id
 * @property null|int $attached_to_id
 * @property null|string $attached_to_type
 * @property-read null|\Creasi\Base\Contracts\HasFileUploads $attachedTo
 */
class FileAttached extends MorphPivot
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'file_upload_id',
    ];

    protected $casts = [
        'attached_to_id' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachedTo()
    {
        return $this->morphTo('attached_to');
    }
}
