<?php

namespace Tests\Integration\Http\Controllers;

use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Requests\StoreCollectionItemRequest;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

// Integration tests are used to test the integration between different parts of the application e.g. db and controller
class CollectionItemTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    protected CollectionItemController $collectionItemController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->underTest = app()[CollectionItemController::class];
    }

    /**
     * A test to check that collection items can be added to the database
     * @return void
     * @throws BindingResolutionException
     */
    public function test_collection_item_can_be_added_to_database()
    {
        /* Arrange */
        // Find a user to act as
        $user = User::find(1);
        $this->actingAs($user);

        // Create collection item data
       $data = [
           'title' => 'Derek\'s Game',
           'description' => 'Derek\'s Game Description',
           'barcode' => '123456789',
           'value' => 10.00,
           'price_paid' => 5.00,
           'thumbnail' => null,
           'cex_image' => null,
           'created_at' => now(),
           'updated_at' => now(),
           'category_id' => 1
       ];

        // create store collection item request
        $request = StoreCollectionItemRequest::create(
            '',
            'POST',
            $data
        );

        // Set the validator on the request
        $request->setValidator(app()['validator']->make($data, $request->rules(), $request->messages(), $request->attributes()));

        /* Act */
        //Save the item to the using the collection item controller
        $this->underTest->store($request);

        /* Assert */
        // Check that the item has been added to the database
        $this->assertDatabaseHas('collection_items', [
            'title' => 'Derek\'s Game',
            'description' => 'Derek\'s Game Description',
            'barcode' => '123456789',
            'value' => 10.00,
            'price_paid' => 5.00,
            'thumbnail' => null,
            'cex_image' => null,
            'category_id' => 1
        ]);

    }
}
