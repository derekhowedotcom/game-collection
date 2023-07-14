<?php

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

//get cex details
//TODO: move this back inside group
//**********************move this back inside group */
Route::get('cex-item-details/{barcode?}', [CexController::class, 'getCexItemDetails']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    //This is an extra route needed for updates to images due to put not supporting form data
    Route::post('collection-items/{collection_item}', [\App\Http\Controllers\Api\CollectionItemController::class, 'update']);
    Route::apiResource('collection-items', \App\Http\Controllers\Api\CollectionItemController::class);
    Route::get('categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

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

