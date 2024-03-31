<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Personnel;
use Creasi\Base\Http\Requests\Personnel\DeleteRequest;
use Creasi\Base\Http\Requests\Personnel\IndexRequest;
use Creasi\Base\Http\Requests\Personnel\StoreRequest;
use Creasi\Base\Http\Requests\Personnel\UpdateRequest;
use Creasi\Base\Http\Resources\Person\PersonCollection;
use Creasi\Base\Http\Resources\Person\PersonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PersonnelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Personnel::class);
    }

    /**
     * Index endpoint for employees.
     */
    public function index(Company $company, IndexRequest $request): PersonCollection
    {
        return new PersonCollection(
            $request->fulfill($company)->paginate()
        );
    }

    /**
     * Create endpoint for employee.
     */
    public function store(Company $company, StoreRequest $request): JsonResponse
    {
        /** @var Personnel */
        $personnel = $request->fulfill($company);

        return $this->show($personnel)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single employee.
     */
    public function show(Personnel $personnel): PersonResource
    {
        return new PersonResource($personnel);
    }

    /**
     * Update endpoint for a single employee.
     */
    public function update(Personnel $personnel, UpdateRequest $request): PersonResource
    {
        $request->fulfill($personnel);

        return $this->show($personnel);
    }

    /**
     * Delete endpoint for a single employee.
     */
    public function destroy(Personnel $personnel, DeleteRequest $request): Response
    {
        $request->fulfill($personnel);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single employee.
     */
    public function restore(Personnel $personnel): PersonResource
    {
        $personnel->restore();

        return $this->show($personnel);
    }
}
