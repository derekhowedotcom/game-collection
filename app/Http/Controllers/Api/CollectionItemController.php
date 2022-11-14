<?php

namespace App\Http\Controllers\Api;

use App\Models\CollectionItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionItemResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCollectionItemRequest;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CollectionItemRepositoryInterface;

class CollectionItemController extends Controller
{
    private CollectionItemRepositoryInterface $collectionItemRepository;

    public function __construct(CollectionItemRepositoryInterface $collectionItemRepository) 
    {
        $this->collectionItemRepository = $collectionItemRepository;
    }

    public function index()
    {
        $collectionItem = $this->collectionItemRepository->getFilteredCollectionItems();
        return CollectionItemResource::collection($collectionItem);
    }

    public function store(StoreCollectionItemRequest $request)
    {
        $this->authorize('collection-items.create'); 
        if ($request->hasFile('thumbnail')) {
            $filename = $request->file('thumbnail')->getClientOriginalName();
            info($filename);
        }

        $collectionItem = $this->collectionItemRepository->createCollectionItem($request->validated());

        return new CollectionItemResource($collectionItem);
    }

    public function update(CollectionItem $collectionItem, StoreCollectionItemRequest $request)
    {
        $this->authorize('collection-items.update'); 

        $collectionItem = $this->collectionItemRepository->updateCollectionItem($collectionItem->id, $request->validated());

        return new CollectionItemResource($collectionItem);
    }

    public function show(CollectionItem $collectionItem)
    {
        return new CollectionItemResource($collectionItem);
    }

    public function destroy(CollectionItem $collectionItem)
    {
        $this->authorize('collection-items.delete');   
        $this->collectionItemRepository->deleteCollectionItem($collectionItem->id);

        return response()->noContent();
    }



}
