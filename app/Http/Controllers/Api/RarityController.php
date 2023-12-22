<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RarityResource;
use App\Models\Rarity;
use Illuminate\Http\Request;
use App\Repositories\RarityRepository;

class RarityController extends Controller
{
    private RarityRepository $rarityRepository;

    public function __construct(RarityRepository $rarityRepository)
    {
        $this->rarityRepository = $rarityRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|RarityResource[]
     */
    public function index()
    {
        return $this->rarityRepository->getRarities();
    }

}
