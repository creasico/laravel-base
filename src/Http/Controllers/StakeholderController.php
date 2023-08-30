<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Http\Requests\Stakeholder\StoreRequest;
use Creasi\Base\Http\Requests\Stakeholder\UpdateRequest;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderCollection;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderResource;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Stakeholder::class);
    }

    /**
     * @return StakeholderCollection
     */
    public function index(Company $company, BusinessRelativeType $type)
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
    public function store(StoreRequest $request, Company $company, BusinessRelativeType $type)
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
        $stakeholder->update($request->validated());

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
