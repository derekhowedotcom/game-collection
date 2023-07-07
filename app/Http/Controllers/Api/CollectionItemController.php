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
use Intervention\Image\ImageManagerStatic as Image;

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

        //validate fields
        $requestValues = $request->validated();

        //TODO: Move to image service?
        if ($request->hasFile('thumbnail')) {
            //TODO: better file name using title ?
            $filename = $requestValues['category_id']. '-' .$request->file('thumbnail')->getClientOriginalName();
            $path = storage_path().'/app/public/images/collection-items/' . $filename;

            Image::make($request->thumbnail->getRealPath())->resize(300, 200)->save($path);

            //overwrite thumbnail with the name ready to store in db    
            $requestValues['thumbnail'] = $filename; 
        }
        
        $collectionItem = $this->collectionItemRepository->createCollectionItem($requestValues);

        return new CollectionItemResource($collectionItem);
    }

    public function update(CollectionItem $collectionItem, StoreCollectionItemRequest $request)
    {
        
        $this->authorize('collection-items.update'); 

        //validate fields
        $requestValues = $request->validated();

        //TODO: Move to image service?
        if ($request->hasFile('thumbnail')) {
            //TODO: better file name using title ?
            $filename = $requestValues['category_id']. '-' .$request->file('thumbnail')->getClientOriginalName();
            $path = storage_path().'/app/public/images/collection-items/' . $filename;

            Image::make($request->thumbnail->getRealPath())->resize(300, 200)->save($path);

            //overwrite thumbnail with the name ready to store in db    
            $requestValues['thumbnail'] = $filename;
          
        }

        

        $collectionItem = $this->collectionItemRepository->updateCollectionItem($collectionItem->id, $requestValues);

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
