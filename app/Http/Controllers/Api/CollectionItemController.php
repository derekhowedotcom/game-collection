<?php

namespace App\Http\Controllers\Api;

use App\Models\CollectionItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionItemResource;
use Illuminate\Http\Request;
//this is where the rules for the request is
use App\Http\Requests\StoreCollectionItemRequest;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CollectionItemRepositoryInterface;
use App\Http\Services\ImageService;
use Intervention\Image\ImageManagerStatic as Image;

class CollectionItemController extends Controller
{
    private CollectionItemRepositoryInterface $collectionItemRepository;
    private ImageService $imageService;

    public function __construct(CollectionItemRepositoryInterface $collectionItemRepository, ImageService $imageService)
    {
        // Inject the collection item repository with DI
        $this->collectionItemRepository = $collectionItemRepository;

        // Inject the image service with DI
        $this->imageService = $imageService;
    }

    public function index()
    {
        // Get filtered collection items
        $collectionItem = $this->collectionItemRepository->getFilteredCollectionItems();

        // Return collection items as a resource
        return CollectionItemResource::collection($collectionItem);
    }

    public function store(StoreCollectionItemRequest $request)
    {
        // Does the user have the correct permission
        $this->authorize('collection-items.create');

        // Get validate fields
        $requestValues = $request->validated();

        // Handle the image upload
        $requestValues = $this->imageService->processImage($request, $requestValues);

        //get the image filename
        $filename = $this->imageService->createImageName($request);

        //save the small image
        $this->imageService->saveSmallImage($filename);

        // Create the collection item
        $collectionItem = $this->collectionItemRepository->createCollectionItem($requestValues);

        return new CollectionItemResource($collectionItem);
    }

    public function update(CollectionItem $collectionItem, StoreCollectionItemRequest $request)
    {
        // Does the user have the correct permission
        $this->authorize('collection-items.update');

        //validate fields
        $requestValues = $request->validated();

        // Handle the image upload
        $requestValues = $this->imageService->processImage($request, $requestValues);

        // Update the collection item
        $collectionItem = $this->collectionItemRepository->updateCollectionItem($collectionItem->id, $requestValues);

        return new CollectionItemResource($collectionItem);
    }

    public function show(CollectionItem $collectionItem)
    {
        // Return a single collection item as a resource
        return new CollectionItemResource($collectionItem);
    }

    public function destroy(CollectionItem $collectionItem)
    {
        // Does the user have the correct permission
        $this->authorize('collection-items.delete');

        // Delete the collection item
        $this->collectionItemRepository->deleteCollectionItem($collectionItem->id);

        // Delete the image
        $this->imageService->deleteImage($collectionItem->thumbnail);

        return response()->noContent();
    }
}
