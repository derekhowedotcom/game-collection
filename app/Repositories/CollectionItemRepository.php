<?php

namespace App\Repositories;

use App\Interfaces\CollectionItemRepositoryInterface;
use App\Models\CollectionItem;

class CollectionItemRepository implements CollectionItemRepositoryInterface 
{
   public function getFilteredCollectionItems()
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

            return $collectionItem;
   }

   public function createCollectionItem(array $collectionItemDetails)
   {
        return CollectionItem::create($collectionItemDetails);
   }

   public function updateCollectionItem($collectionItemId, array $collectionItemDetails)
   {
        CollectionItem::whereId($collectionItemId)->update($collectionItemDetails);
        return CollectionItem::findOrFail($collectionItemId);
   }

    public function deleteCollectionItem($collectionItem) 
    {
        CollectionItem::destroy($collectionItem);
    }
    
}