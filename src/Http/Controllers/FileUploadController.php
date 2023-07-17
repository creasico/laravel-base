<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Contracts\HasFileUploads;
use Creasi\Base\Http\Requests\FileUpload\StoreRequest;
use Creasi\Base\Http\Requests\FileUpload\UpdateRequest;
use Creasi\Base\Http\Resources\FileUpload\FileUploadCollection;
use Creasi\Base\Http\Resources\FileUpload\FileUploadResource;
use Creasi\Base\Models\FileUpload;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(FileUpload::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, FileUpload>
     */
    public function index(HasFileUploads $entity)
    {
        return new FileUploadCollection($entity->files()->paginate());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, HasFileUploads $entity)
    {
        $item = $request->storeFor($entity);

        return $this->show($item, $request)->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FileUpload $file, Request $request)
    {
        return FileUploadResource::make($file)->toResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, FileUpload $file)
    {
        $file->update($request->validated());

        return $this->show($file, $request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FileUpload $file)
    {
        $file->delete();

        return response()->noContent();
    }
}
