<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Http\Requests\File\DeleteRequest;
use Creasi\Base\Http\Requests\File\IndexRequest;
use Creasi\Base\Http\Requests\File\StoreRequest;
use Creasi\Base\Http\Requests\File\UpdateRequest;
use Creasi\Base\Http\Resources\File\FileCollection;
use Creasi\Base\Http\Resources\File\FileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FileController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(File::class);
    }

    /**
     * Index endpoint for files.
     */
    public function index(Entity $entity, IndexRequest $request): FileCollection
    {
        return new FileCollection(
            $request->fulfill($entity)->paginate()
        );
    }

    /**
     * Create endpoint for file.
     */
    public function store(Entity $entity, StoreRequest $request): JsonResponse
    {
        $file = $request->fulfill($entity);

        return $this->show($file)->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show endpoint for a single file.
     */
    public function show(File $file): FileResource
    {
        return new FileResource($file);
    }

    /**
     * Update endpoint for a single file.
     */
    public function update(File $file, UpdateRequest $request): FileResource
    {
        $request->fulfill($file);

        return $this->show($file);
    }

    /**
     * Delete endpoint for a single file.
     */
    public function destroy(File $file, DeleteRequest $request): Response
    {
        $request->fulfill($file);

        return response()->noContent();
    }

    /**
     * Restore endpoint for a single file.
     */
    public function restore(File $file): FileResource
    {
        $file->restore();

        return $this->show($file);
    }
}
