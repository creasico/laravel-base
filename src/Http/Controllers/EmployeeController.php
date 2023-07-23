<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Http\Requests\Employee\StoreRequest;
use Creasi\Base\Http\Requests\Employee\UpdateRequest;
use Creasi\Base\Http\Resources\Employee\EmployeeCollection;
use Creasi\Base\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Employee::class);
    }

    /**
     * @return EmployeeCollection
     */
    public function index(Employee $employee)
    {
        $items = $employee->newInstance()->latest();

        return new EmployeeCollection($items->paginate());
    }

    /**
     * @return EmployeeResource
     */
    public function store(StoreRequest $request, Employee $employee)
    {
        /** @var Employee */
        $item = $employee->create($request->validated());

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return EmployeeResource
     */
    public function show(Employee $employee, Request $request)
    {
        return EmployeeResource::make($employee)->toResponse($request);
    }

    /**
     * @return EmployeeResource
     */
    public function update(UpdateRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return $this->show($employee, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->noContent();
    }
}