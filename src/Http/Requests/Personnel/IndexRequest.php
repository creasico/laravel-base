<?php

namespace Creasi\Base\Http\Requests\Personnel;

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
        $items = $company->employees()->latest();

        return $items;
    }
}
