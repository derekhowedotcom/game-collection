<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Requests\StoreCollectionItemRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// Feature tests are used to test the functionality of the application
class CollectionItemTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    protected CollectionItemController $collectionItemController;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->underTest = app()[CollectionItemController::class];
        $this->user = User::find(1);
    }

    /**
     * A test to check that collection items can be added to the database
     * @return void
     */
    public function test_collection_item_can_be_added_to_database_with_route()
    {
        /* Arrange */
        // Find a user to act as
        $this->actingAs($this->user);

        // Create collection item data
        $data = [
            'title' => 'Derek\'s Game',
            'description' => 'Derek\'s Game Description',
            'barcode' => '123456789',
            'value' => 10.00,
            'price_paid' => 5.00,
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
            'cex_image' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'category_id' => 1
        ];

        /* Act */
        // create store collection item request
        $this->post('/api/collection-items', $data);

        /* Assert */
        // Check that the item has been added to the database
        $this->assertDatabaseHas('collection_items', [
            'title' => 'Derek\'s Game',
            'description' => 'Derek\'s Game Description',
            'barcode' => '123456789',
            'value' => 10.00,
            'price_paid' => 5.00,
            'thumbnail' => '1-thumbnail.jpg',
            'cex_image' => null,
            'category_id' => 1
        ]);

    }
}

