<?php

namespace Creasi\Base\Http\Requests\Stakeholder;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Base\Http\Requests\AbstractIndexRequest;

class IndexRequest extends AbstractIndexRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return \array_merge(parent::rules(), []);
    }

    public function fulfill(Company $company, StakeholderType $type)
    {
        $items = $company->stakeholders()->where([
            'type' => $type,
        ])->latest('id');

        $items->with('stakeholder');

        return $items;
    }
}
