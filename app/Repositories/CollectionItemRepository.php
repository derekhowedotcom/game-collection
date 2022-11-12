<?php

namespace App\Repositories;

use App\Interfaces\CollectionItemRepositoryInterface;
use App\Models\CollectionItem;

class CollectionItemRepository implements CollectionItemRepositoryInterface 
{
   
    public function deleteCollectionItem($collectionItem) 
    {
        CollectionItem::destroy($collectionItem);
    }
    
}