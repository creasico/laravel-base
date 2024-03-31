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
    public function index(Company $company, IndexRequest $request): OrganizationCollection
    {
        return new OrganizationCollection(
            $request->fulfill($company)->paginate()
        );
    }

    /**
     * Create endpoint for company.
     */
    public function store(Company $company, StoreRequest $request): JsonResponse
    {
        $company = $request->fulfill($company);

        return $this->show($company)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single company.
     */
    public function show(Company $company): OrganizationResource
    {
        return new OrganizationResource($company);
    }

    /**
     * Update endpoint for a single company.
     */
    public function update(Company $company, UpdateRequest $request): OrganizationResource
    {
        $request->fulfill($company);

        return $this->show($company);
    }

    /**
     * Delete endpoint for a single company.
     */
    public function destroy(Company $company, DeleteRequest $request): Response
    {
        $request->fulfill($company);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single company.
     */
    public function restore(Company $company): OrganizationResource
    {
        $company->restore();

        return $this->show($company->refresh());
    }
}
