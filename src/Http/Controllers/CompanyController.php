<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\Company\StoreRequest;
use Creasi\Base\Http\Requests\Company\UpdateRequest;
use Creasi\Base\Http\Resources\Company\CompanyCollection;
use Creasi\Base\Http\Resources\Company\CompanyResource;
use Creasi\Base\Models\Business;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Company::class);
    }

    /**
     * @return CompanyCollection
     */
    public function index()
    {
        $items = Business::query()->latest();

        return new CompanyCollection($items->paginate());
    }

    /**
     * @return CompanyResource
     */
    public function store(StoreRequest $request)
    {
        /** @var Business $item */
        $item = Business::create($request->validated());

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return CompanyResource
     */
    public function show(Business $company, Request $request)
    {
        return CompanyResource::make($company)->toResponse($request);
    }

    /**
     * @return CompanyResource
     */
    public function update(UpdateRequest $request, Business $company)
    {
        $company->update($request->validated());

        return $this->show($company, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $company)
    {
        $company->delete();

        return response()->noContent();
    }
}
