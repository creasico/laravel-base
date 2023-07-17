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
     * @return \Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public function index(Request $request, Employee $employee)
    {
        $users = $employee->newInstance()
            ->where('id', '<>', $request->user()->id)
            ->latest();

        return new EmployeeCollection($users->paginate());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, Employee $employee)
    {
        /** @var Employee */
        $item = $employee->create($request->validated());

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Employee $employee, Request $request)
    {
        return EmployeeResource::make($employee)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return $this->show($employee, $request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->noContent();
    }
}
