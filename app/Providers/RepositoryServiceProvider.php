<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CollectionItemRepositoryInterface;
use App\Repositories\CollectionItemRepository;
use App\Interfaces\RarityRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CollectionItemRepositoryInterface::class, CollectionItemRepository::class);
        $this->app->bind(RarityRepositoryInterface::class, RarityRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
