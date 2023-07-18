<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Http\Requests\Stakeholder\StoreRequest;
use Creasi\Base\Http\Requests\Stakeholder\UpdateRequest;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderCollection;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderResource;
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
    public function index(Stakeholder $stakeholder)
    {
        $items = $stakeholder->newQuery()->latest();

        return new StakeholderCollection($items->paginate());
    }

    /**
     * @return StakeholderResource
     */
    public function store(StoreRequest $request, Stakeholder $stakeholder)
    {
        /** @var Stakeholder */
        $item = $stakeholder->create($request->validated());

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
