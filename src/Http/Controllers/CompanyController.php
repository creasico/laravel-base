<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Http\Requests\Company\DeleteRequest;
use Creasi\Base\Http\Requests\Company\IndexRequest;
use Creasi\Base\Http\Requests\Company\StoreRequest;
use Creasi\Base\Http\Requests\Company\UpdateRequest;
use Creasi\Base\Http\Resources\Organization\OrganizationCollection;
use Creasi\Base\Http\Resources\Organization\OrganizationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    /**
     * Index endpoint for companies.
     */
    public function index(Company $org, IndexRequest $request): OrganizationCollection
    {
        return new OrganizationCollection(
            $request->fulfill($org)->paginate()
        );
    }

    /**
     * Create endpoint for company.
     */
    public function store(Company $org, StoreRequest $request): JsonResponse
    {
        $org = $request->fulfill($org);

        return $this->show($org)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single company.
     */
    public function show(Company $org): OrganizationResource
    {
        return new OrganizationResource($org);
    }

    /**
     * Update endpoint for a single company.
     */
    public function update(Company $org, UpdateRequest $request): OrganizationResource
    {
        $request->fulfill($org);

        return $this->show($org);
    }

    /**
     * Delete endpoint for a single company.
     */
    public function destroy(Company $org, DeleteRequest $request): Response
    {
        $request->fulfill($org);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single company.
     */
    public function restore(Company $org): OrganizationResource
    {
        $org->restore();

        return $this->show($org);
    }
}
