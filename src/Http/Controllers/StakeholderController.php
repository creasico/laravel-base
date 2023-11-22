<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Base\Http\Requests\Stakeholder\StoreRequest;
use Creasi\Base\Http\Requests\Stakeholder\UpdateRequest;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderCollection;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderResource;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Stakeholder::class);
    }

    /**
     * @return StakeholderCollection
     */
    public function index(Company $company, StakeholderType $type)
    {
        $items = $company->stakeholders()->where([
            'type' => $type,
        ])->latest('id');

        $items->with('stakeholder');

        return new StakeholderCollection($items->paginate());
    }

    /**
     * @return StakeholderResource
     */
    public function store(StoreRequest $request, Company $company, StakeholderType $type)
    {
        $item = $request->fulfill($company, $type);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return StakeholderResource
     */
    public function show(Stakeholder $stakeholder, Request $request)
    {
        return StakeholderResource::make($stakeholder)->toResponse($request);
    }

    /**
     * @return StakeholderResource
     */
    public function update(UpdateRequest $request, Stakeholder $stakeholder)
    {
        $request->fulfill($stakeholder);

        return $this->show($stakeholder, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stakeholder $stakeholder)
    {
        $stakeholder->delete();

        return response()->noContent();
    }
}
