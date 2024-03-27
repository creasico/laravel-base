<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Http\Requests\Address\StoreRequest;
use Creasi\Base\Http\Requests\Address\UpdateRequest;
use Creasi\Base\Http\Resources\Address\AddressCollection;
use Creasi\Base\Http\Resources\Address\AddressResource;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Address::class);
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Entity $entity)
    {
        $item = $request->fulfill($entity);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address, Request $request)
    {
        return AddressResource::make($address)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Address $address)
    {
        $request->fulfill($address);

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
