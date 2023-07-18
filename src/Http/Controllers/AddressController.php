<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\Address\StoreRequest;
use Creasi\Base\Http\Requests\Address\UpdateRequest;
use Creasi\Base\Http\Resources\Address\AddressCollection;
use Creasi\Base\Http\Resources\Address\AddressResource;
use Creasi\Base\Models\Address;
use Creasi\Base\Models\Entity;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Address::class);
    }

    /**
     * @return AddressCollection
     */
    public function index(Entity $entity)
    {
        $items = $entity->addresses()->latest();

        return new AddressCollection($items->paginate());
    }

    /**
     * @return AddressResource
     */
    public function store(StoreRequest $request, Entity $entity)
    {
        $item = $request->storeFor($entity);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return AddressResource
     */
    public function show(Address $address, Request $request)
    {
        return AddressResource::make($address)->toResponse($request);
    }

    /**
     * @return AddressResource
     */
    public function update(UpdateRequest $request, Address $address)
    {
        $address->update($request->validated());

        return $this->show($address, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return response()->noContent();
    }
}
