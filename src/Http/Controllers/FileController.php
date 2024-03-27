<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Http\Requests\File\StoreRequest;
use Creasi\Base\Http\Requests\File\UpdateRequest;
use Creasi\Base\Http\Resources\File\FileCollection;
use Creasi\Base\Http\Resources\File\FileResource;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(File::class);
    }

    /**
     * @return FileCollection
     */
    public function index(Entity $entity)
    {
        $items = $entity->files()->latest();

        return new FileCollection($items->paginate());
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
    public function show(File $file, Request $request)
    {
        return FileResource::make($file)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, File $file)
    {
        $request->fulfill($file);

        return $this->show($file, $request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $file->delete();

        return response()->noContent();
    }
}
