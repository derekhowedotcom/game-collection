<?php

namespace App\Repositories;

use App\Http\Resources\RarityResource;
use App\Models\Rarity;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RarityRepository
{
    /**
     * Get all rarities
     *
     * @return AnonymousResourceCollection
     */
    public function getRarities(): AnonymousResourceCollection
    {
        // Get all rarities
        return RarityResource::collection(Rarity::all());
    }

}
