<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\Address\StoreRequest;
use Creasi\Base\Http\Requests\Address\UpdateRequest;
use Creasi\Base\Http\Resources\Address\AddressCollection;
use Creasi\Base\Http\Resources\Address\AddressResource;
use Creasi\Base\Models\Address;
use Creasi\Nusa\Contracts\HasAddresses;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Address>
     */
    public function index(HasAddresses $entity)
    {
        return new AddressCollection($entity->addresses()->paginate());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, HasAddresses $entity)
    {
        $item = $request->storeFor($entity);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Address $address, Request $request)
    {
        return AddressResource::make($address)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Address $address)
    {
        $address->update($request->validated());

        return $this->show($address, $request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return response()->noContent();
    }
}
