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

   // Get total count of collection items for category
    public function getTotalCountForCategory($categoryId): int
    {
          return CollectionItem::where('category_id', $categoryId)->count();
    }

    // Get total count of collection items
    public function getTotalCount(): int
    {
          return CollectionItem::count();
    }

    // Get total count of collection items based on category name
    public function getTotalCountForCategoryName($categoryName): int
    {
          return CollectionItem::whereHas('category', function($query) use ($categoryName) {
              $query->where('name', $categoryName);
          })->count();
    }

    // Get total count of collection items based on category name like
    public function getTotalCountForCategoryNameLike($categoryName): int
    {
          return CollectionItem::whereHas('category', function($query) use ($categoryName) {
              $query->where('name', 'like', '%'.$categoryName.'%');
          })->count();
    }

    // Get array of counts for each category based on an array of category names like
    public function getCountsForCategoryNamesLike($categoryNames): array
    {
        // Convert category names to array
        $categoryNames = explode(',', $categoryNames);
        $counts = [];

        // Loop through category names and get count for each
        foreach ($categoryNames as $categoryName) {
            // Add count to array with key as category name in lowercase with spaces replaced with hyphens
            $counts[strtolower(str_replace(' ', '-', $categoryName))] = $this->getTotalCountForCategoryNameLike($categoryName);
        }

        // Add total count to array with key as 'total'
        $counts['total'] = $this->getTotalCount();

        // Return array of counts
        return $counts;
    }

    // Get total value of collection items for category
    public function getTotalValueForCategory($categoryId): int
    {
          return CollectionItem::where('category_id', $categoryId)->sum('value');
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

    public function deleteCollectionItem($collectionItem): void
    {
        CollectionItem::destroy($collectionItem);
    }

}
