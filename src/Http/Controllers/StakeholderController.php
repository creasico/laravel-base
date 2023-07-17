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
     * @return \Illuminate\Database\Eloquent\Collection<int, Stakeholder>
     */
    public function index(Request $request, Stakeholder $stakeholder)
    {
        $model = $stakeholder->newQuery()
            ->where('id', '<>', $request->user()->id)
            ->latest();

        return new StakeholderCollection($model->paginate());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, Stakeholder $stakeholder)
    {
        /** @var Stakeholder */
        $model = $stakeholder->create($request->validated());

        return $this->show($model, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Stakeholder $stakeholder, Request $request)
    {
        return StakeholderResource::make($stakeholder)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Stakeholder $stakeholder)
    {
        $stakeholder->update($request->validated());

        return $this->show($stakeholder, $request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Stakeholder $stakeholder)
    {
        $stakeholder->delete();

        return response()->noContent();
    }
}
