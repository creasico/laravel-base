<?php

namespace Creasi\Base\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Collection extends ResourceCollection
{
    public function withResponse(Request $request, JsonResponse $response)
    {
        if ($this->collection->isEmpty()) {
            $response->setStatusCode(404);
            return;
        }
    }
}
