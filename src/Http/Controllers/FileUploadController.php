<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\FileUpload\StoreRequest;
use Creasi\Base\Http\Requests\FileUpload\UpdateRequest;
use Creasi\Base\Http\Resources\FileUpload\FileUploadCollection;
use Creasi\Base\Http\Resources\FileUpload\FileUploadResource;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\FileUpload;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(FileUpload::class);
    }

    /**
     * @return FileUploadCollection
     */
    public function index(Entity $entity)
    {
        $items = $entity->files()->latest();

        return new FileUploadCollection($items->paginate());
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Entity $entity)
    {
        $item = $request->storeFor($entity);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show(FileUpload $model, Request $request, string $file = null)
    {
        $file = $model->exists ? $model : $model->newQuery()->findOrFail($file);

        return FileUploadResource::make($file)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, FileUpload $model, string $file = null)
    {
        $file = $model->newQuery()->findOrFail($file);

        $file->update($request->validated());

        return $this->show($file, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileUpload $model, string $file = null)
    {
        $file = $model->newQuery()->findOrFail($file);

        $file->delete();

        return response()->noContent();
    }
}
