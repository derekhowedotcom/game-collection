<?php

namespace Tests\Integration\Http\Controllers;

use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Requests\StoreCollectionItemRequest;
use App\Http\Resources\CollectionItemResource;
use App\Models\CollectionItem;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        $request->setValidator(
            app()['validator']->make($updateData, $request->rules(), $request->messages(), $request->attributes())
        );

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

    /**
     * A test to check that collection items can be deleted from the database
     * @return void
     * @throws BindingResolutionException
     */
    public function test_collection_item_can_be_deleted_from_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to delete later
        CollectionItem::Create($this->defaultItemData[0]);

        // Find the collection item to delete last inserted into the database
        $collectionItem = CollectionItem::orderBy('id', 'desc')->first();

        /* Act */
        // Delete the item to the using the collection item controller
        $this->underTest->destroy($collectionItem);

        /* Assert */
        // Check that the item has been deleted from the database
        $this->assertDatabaseMissing('collection_items', [
            'title' => 'Grand Theft Auto V (5)',
            'description' => 'One of the biggest events of the last console generation, Grand Theft Auto V is a masterpiece of game design.',
            'barcode' => '123456789',
            'value' => 10.00,
            'price_paid' => 5.00,
            'thumbnail' => null,
            'cex_image' => null,
            'category_id' => 1
        ]);
    }

    /**
     * A test to check that collection items can be retrieved from the database
     * @return void
     */
    public function test_collection_item_can_be_retrieved_from_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to retrieve later
        CollectionItem::Create($this->defaultItemData[0]);

        // Find the collection item to retrieve last inserted into the database
        $collectionItem = CollectionItem::orderBy('id', 'desc')->first();

        /* Act */
        // Retrieve the item to the using the collection item controller
        $collectionItemFromDb = $this->underTest->show($collectionItem);

        /* Assert */
        // Check that the item has been retrieved from the database and is a collection item resource
        $this->assertInstanceOf(CollectionItemResource::class, $collectionItemFromDb);
        $this->assertEquals($this->defaultItemData[0]['title'], $collectionItemFromDb->title);
        $this->assertEquals($this->defaultItemData[0]['description'], $collectionItemFromDb->description);
    }

    /**
     * A test to check that collection items with categories can be counted from the database
     * @return void
     */
    public function test_collection_items_with_categories_can_be_counted_from_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to count later
        CollectionItem::Create($this->defaultItemData[0]);

        // Find the collection item to count last inserted into the database
        $collectionItem = CollectionItem::orderBy('id', 'desc')->first();

        /* Act */
        // Count the item to the using the collection item controller
        $collectionItemCount = $this->underTest->countForCategoryNameLike('Hardware');

        /* Assert */
        // Check that the item has been counted from the database and is greater than or equal to 1 (due to the item created above + random seeder data)
        $this->assertGreaterThanOrEqual(1, $collectionItemCount);
    }

    /**
     * A test to check that multiple collection item categories can be counted from the database
     * @return void
     */
    public function test_collection_items_with_multiple_categories_can_be_counted_from_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to count later
        CollectionItem::Create($this->defaultItemData[0]);

        // Find the collection item to count last inserted into the database
        $collectionItem = CollectionItem::orderBy('id', 'desc')->first();

        /* Act */
        // Count the item to the using the collection item controller
        $collectionItemCount = $this->underTest->multiCountForCategoryNameLike('Software,Hardware');

        /* Assert */
        // Check that the item has been counted from the database and is greater than or equal to 1 (due to the item created above + random seeder data)
        $this->assertGreaterThanOrEqual(1, $collectionItemCount);
    }

    /**
     * A test to check that filtered collection items can be retrieved from the database
     * @return void
     */
    public function test_filtered_collection_items_can_be_retrieved_from_database()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create a collection item ready to retrieve later
        CollectionItem::Factory()->count(10)->create();

        /* Act */
        // Retrieve the items from the database using the collection item controller
        $collectionItemFromDb = $this->underTest->index();

        /* Assert */
        // Check that the collection has 10 items or more
        $this->assertEquals(10, $collectionItemFromDb->count());
        $this->assertInstanceOf(AnonymousResourceCollection::class, $collectionItemFromDb);
        foreach ($collectionItemFromDb->collection as $collectionItem) {
            $this->assertInstanceOf(CollectionItemResource::class, $collectionItem);
        }
    }

    /**
     * Build test data to use in the tests
     * @return void
     */
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
