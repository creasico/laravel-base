<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Http\Requests\Employee\DeleteRequest;
use Creasi\Base\Http\Requests\Employee\IndexRequest;
use Creasi\Base\Http\Requests\Employee\StoreRequest;
use Creasi\Base\Http\Requests\Employee\UpdateRequest;
use Creasi\Base\Http\Resources\Employee\EmployeeCollection;
use Creasi\Base\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Employee::class);
    }

    /**
     * Index endpoint for employees.
     */
    public function index(Company $company, IndexRequest $request): EmployeeCollection
    {
        return new EmployeeCollection(
            $request->fulfill($company)->paginate()
        );
    }

    /**
     * Create endpoint for employee.
     */
    public function store(Company $company, StoreRequest $request): JsonResponse
    {
        /** @var Employee */
        $employee = $request->fulfill($company);

        return $this->show($employee)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single employee.
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    /**
     * Update endpoint for a single employee.
     */
    public function update(Employee $employee, UpdateRequest $request): EmployeeResource
    {
        $request->fulfill($employee);

        return $this->show($employee);
    }

    /**
     * Delete endpoint for a single employee.
     */
    public function destroy(Employee $employee, DeleteRequest $request): Response
    {
        $request->fulfill($employee);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single employee.
     */
    public function restore(Employee $employee): EmployeeResource
    {
        $employee->restore();

        return $this->show($employee);
    }
}
