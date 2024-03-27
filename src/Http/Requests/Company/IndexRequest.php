<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Database\Models\Contracts\Company;
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

    public function fulfill(Company $company)
    {
        $items = $company->subsidiaries()->latest('id');

        $items->with('stakeholder');

        return $items;
    }
}
