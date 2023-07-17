<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\Company\StoreRequest;
use Creasi\Base\Http\Requests\Company\UpdateRequest;
use Creasi\Base\Http\Resources\CompanyResource;
use Creasi\Base\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Company>
     */
    public function index(Request $request)
    {
        $users = Company::query()
            ->where('id', '<>', $request->user()->id)
            ->latest();

        return CompanyResource::collection($users->paginate());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var Company $item */
        $item = Company::create($request->validated());

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company, Request $request)
    {
        return CompanyResource::make($company)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $company->update($request->validated());

        return $this->show($company, $request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->noContent();
    }
}
