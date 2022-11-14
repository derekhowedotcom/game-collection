<?php

namespace App\Interfaces;

interface CollectionItemRepositoryInterface 
{
   public function getFilteredCollectionItems();
    public function deleteCollectionItem($collectionItem);
    public function createCollectionItem(array $collectionItemDetails);
    public function updateCollectionItem($collectionItemId, array $collectionItemDetails);
}