<?php

namespace Tests\Integration\Http\Controllers;

use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Requests\StoreCollectionItemRequest;
use App\Models\CollectionItem;
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
    protected User $user;
    protected array $defaultItemData;


    protected function setUp(): void
    {
        parent::setUp();

        $this->underTest = app()[CollectionItemController::class];
        $this->user = User::find(1);

        $this->buildTestData();

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
        $this->actingAs($this->user);

        // Create collection item data
       $data = $this->defaultItemData[0];

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
        $this->assertDatabaseHas('collection_items', $this->defaultItemData[0]);

    }

    /**
     * A test to check that collection items can be updated in the database
     * @return void
     * @throws BindingResolutionException
     */
    public function test_collection_item_can_be_updated_in_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to update later
        CollectionItem::Create($this->defaultItemData[1]);

        // Create collection item data to use in the update
        $updateData = [
            'title' => 'Grand Theft Auto VI (6)',
            'description' => 'The all new GTA game we have been waiting for, for over 10 years!.',
            'barcode' => '987654321',
            'value' => 70.00,
            'price_paid' => 60.00,
            'thumbnail' => null,
            'cex_image' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'category_id' => 48
        ];

        // Find the collection item to update last inserted into the database
        $collectionItem = CollectionItem::orderBy('id', 'desc')->first();

        // create store collection item request
        $request = StoreCollectionItemRequest::create(
            '',
            'PUT',
            $updateData
        );

        // Set the validator on the request
        $request->setValidator(app()['validator']->make($updateData, $request->rules(), $request->messages(), $request->attributes()));

        /* Act */
        // Update the item to the using the collection item controller
        $this->underTest->update($collectionItem, $request);

        /* Assert */
        // Check that the item has been updated in the database
        $this->assertDatabaseHas('collection_items', [
            'title' => 'Grand Theft Auto VI (6)',
            'description' => 'The all new GTA game we have been waiting for, for over 10 years!.',
            'barcode' => '987654321',
            'value' => 70.00,
            'price_paid' => 60.00,
            'thumbnail' => null,
            'cex_image' => null,
            'category_id' => 48
        ]);
    }

    private function buildTestData()
    {
        // Create array to store both items
        $this->defaultItemData = [
            [
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
            ],
            [
                'title' => 'Grand Theft Auto V (5)',
                'description' => 'One of the biggest events of the last console generation, Grand Theft Auto V is a masterpiece of game design.',
                'barcode' => '123456789',
                'value' => 10.00,
                'price_paid' => 5.00,
                'thumbnail' => null,
                'cex_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'category_id' => 1
            ]
        ];
    }

}
