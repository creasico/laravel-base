<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Base\Http\Requests\Stakeholder\DeleteRequest;
use Creasi\Base\Http\Requests\Stakeholder\IndexRequest;
use Creasi\Base\Http\Requests\Stakeholder\StoreRequest;
use Creasi\Base\Http\Requests\Stakeholder\UpdateRequest;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderCollection;
use Creasi\Base\Http\Resources\Stakeholder\StakeholderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StakeholderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Stakeholder::class);
    }

    /**
     * Index endpoint for stakeholders.
     */
    public function index(Company $company, StakeholderType $type, IndexRequest $request): StakeholderCollection
    {
        return new StakeholderCollection(
            $request->fulfill($company, $type)->paginate()
        );
    }

    /**
     * Create endpoint for stakeholder.
     */
    public function store(Company $company, StakeholderType $type, StoreRequest $request): JsonResponse
    {
        $stakeholder = $request->fulfill($company, $type);

        return $this->show($stakeholder)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single stakeholder.
     */
    public function show(Stakeholder $stakeholder): StakeholderResource
    {
        return new StakeholderResource($stakeholder);
    }

    /**
     * Update endpoint for a single stakeholder.
     */
    public function update(Stakeholder $stakeholder, UpdateRequest $request): StakeholderResource
    {
        $request->fulfill($stakeholder);

        return $this->show($stakeholder);
    }

    /**
     * Delete endpoint for a single stakeholder.
     */
    public function destroy(Stakeholder $stakeholder, DeleteRequest $request): Response
    {
        $request->fulfill($stakeholder);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single stakeholder.
     */
    public function restore(Stakeholder $stakeholder): StakeholderResource
    {
        $stakeholder->restore();

        return $this->show($stakeholder);
    }
}
