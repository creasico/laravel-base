<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Document;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Document> $documents
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasDocuments
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function documents()
    {
        return $this->morphToMany(Document::class, 'attached_to', 'attachments', null, 'document_id');
    }
}
