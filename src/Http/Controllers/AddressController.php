<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Http\Requests\Address\DeleteRequest;
use Creasi\Base\Http\Requests\Address\IndexRequest;
use Creasi\Base\Http\Requests\Address\StoreRequest;
use Creasi\Base\Http\Requests\Address\UpdateRequest;
use Creasi\Base\Http\Resources\Address\AddressCollection;
use Creasi\Base\Http\Resources\Address\AddressResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Address::class);
    }

    /**
     * Index endpoint for addresses.
     */
    public function index(Entity $entity, IndexRequest $request): AddressCollection
    {
        return new AddressCollection(
            $request->fulfill($entity)->paginate()
        );
    }

    /**
     * Create endpoint for address.
     */
    public function store(Entity $entity, StoreRequest $request): JsonResponse
    {
        $address = $request->fulfill($entity);

        return $this->show($address)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single address.
     */
    public function show(Address $address): AddressResource
    {
        return new AddressResource($address);
    }

    /**
     * Update endpoint for a single address.
     */
    public function update(Address $address, UpdateRequest $request): AddressResource
    {
        $request->fulfill($address);

        return $this->show($address);
    }

    /**
     * Delete endpoint for a single address.
     */
    public function destroy(Address $address, DeleteRequest $request): Response
    {
        $request->fulfill($address);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single address.
     */
    public function restore(Address $address): AddressResource
    {
        $address->restore();

        return $this->show($address);
    }
}
