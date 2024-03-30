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
        $person = $request->fulfill($company);

        return $this->show($person)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single employee.
     */
    public function show(Personnel $person): PersonResource
    {
        return new PersonResource($person);
    }

    /**
     * Update endpoint for a single employee.
     */
    public function update(Personnel $person, UpdateRequest $request): PersonResource
    {
        $request->fulfill($person);

        return $this->show($person);
    }

    /**
     * Delete endpoint for a single employee.
     */
    public function destroy(Personnel $person, DeleteRequest $request): Response
    {
        $request->fulfill($person);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single employee.
     */
    public function restore(Personnel $person): PersonResource
    {
        $person->restore();

        return $this->show($person);
    }
}
