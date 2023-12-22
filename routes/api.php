<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Controllers\Api\RarityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Role;
use App\Http\Controllers\Cex\CexController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function(){

// This is an extra route needed for updates to images due to put not supporting form data
    Route::post('collection-items/{collection_item}', [CollectionItemController::class, 'update']);
    Route::apiResource('collection-items', CollectionItemController::class);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('rarities', [RarityController::class, 'index']);

    // Get collection item counts
    Route::get('collection-items/count/{category_name?}', [CollectionItemController::class, 'countForCategoryNameLike']);
    Route::get('collection-items/multi-count/{category_names?}', [CollectionItemController::class, 'multiCountForCategoryNameLike']);

    // Get cex details
    Route::get('cex-item-details/{barcode?}', [CexController::class, 'getCexItemDetails']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Get user roles
    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });

});

