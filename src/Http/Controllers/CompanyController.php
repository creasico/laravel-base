<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Http\Requests\Company\StoreRequest;
use Creasi\Base\Http\Requests\Company\UpdateRequest;
use Creasi\Base\Http\Resources\Company\CompanyCollection;
use Creasi\Base\Http\Resources\Company\CompanyResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    /**
     * @return CompanyCollection
     */
    public function index(Company $company)
    {
        $items = $company->subsidiaries()->latest('id');

        $items->with('stakeholder');

        return new CompanyCollection($items->paginate());
    }

    /**
     * @return CompanyResource
     */
    public function store(StoreRequest $request, Company $company)
    {
        $item = $request->fulfill($company);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return CompanyResource
     */
    public function show(Company $company, Request $request)
    {
        return CompanyResource::make($company)->toResponse($request);
    }

    /**
     * @return CompanyResource
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $request->fulfill($company);

        return $this->show($company, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->noContent();
    }
}
