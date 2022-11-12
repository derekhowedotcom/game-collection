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
        
        $orderColumn = request('order_column', 'title');
        if(!in_array($orderColumn, ['id', 'title', 'created_at'])){
            $orderColumn = 'title';
        }
        $orderDirection = request('order_direction', 'desc');
        if(!in_array($orderDirection, ['asc', 'desc'])){
            $orderDirection = 'asc';
        }

        $collectionItem = CollectionItem::with('category')
        ->when(request('search_category'), function($query){
            $query->where('category_id', request('search_category'));
        })
        ->when(request('search_id'), function($query){
            $query->where('id', request('search_id'));
        })
        ->when(request('search_title'), function($query){
            $query->where('title', 'like',  '%'.request('search_title').'%');
        })
        ->when(request('search_description'), function($query){
            $query->where('description', 'like',  '%'.request('search_description').'%');
        })
        ->when(request('search_global'), function ($query) {
            $query->where(function($q) {
                $q->where('id', request('search_global'))
                    ->orWhere('title', 'like', '%'.request('search_global').'%')
                    ->orWhere('description', 'like', '%'.request('search_global').'%');

            });
        })

        ->orderBy($orderColumn, $orderDirection)
        ->paginate(10);
        return CollectionItemResource::collection($collectionItem);
    }

    public function store(StoreCollectionItemRequest $request)
    {
        $this->authorize('collection-items.create'); 
        if ($request->hasFile('thumbnail')) {
            $filename = $request->file('thumbnail')->getClientOriginalName();
            info($filename);
        }

        $collectionItem = CollectionItem::create($request->validated());

        return new CollectionItemResource($collectionItem);
    }

    public function update(CollectionItem $collectionItem, StoreCollectionItemRequest $request)
    {
        $this->authorize('collection-items.update'); 
        $collectionItem->update($request->validated());

        return new CollectionItemResource($collectionItem);
    }

    public function show(CollectionItem $collectionItem)
    {
        return new CollectionItemResource($collectionItem);
    }

    public function destroy(CollectionItem $collectionItem)
    {
        $this->authorize('collection-items.delete');   
        $collectionItem->delete();

        return response()->noContent();
    }



}
