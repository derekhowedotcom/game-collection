<?php

namespace Tests\Http\Controllers\Cex;

use App\Http\Controllers\Cex\CexController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;


class CexControllerTest extends TestCase
{

    protected String $barcode;
    protected cexController $cexController;
    protected function setUp(): void
    {
        parent::setUp();

//        $this->cexController = app()[CexController::class];
        $this->cexController = Mockery::mock(CexController::class);

        // GTA 5 PS4 Barcode
        $this->barcode = '5026555416986';
    }

    public function test_get_cex_item_details_with_barcode()
    {
        // Set up some mock data
        $expectedData = [
            'response' => [
                'ack' => 'Success',
                'status' => 200,
                'response' => [
                    'data' => [
                        'boxart' => 'https://wss2.cex.uk.webuy.io/images/boxart/ps4gta5.jpg',
                        'category' => 'Gaming',
                        'categoryid' => 1,
                        'currency' => 'GBP',
                        'description' => 'Grand Theft Auto V',
                        'id' => 'ps4gta5',
                        'name' => 'Grand Theft Auto V',
                        'price' => 12,
                        'sell_price' => 12,
                        'superCatId' => 1,
                        'superCatName' => 'Gaming',
                        'title' => 'Grand Theft Auto V',
                        'url' => 'https://uk.webuy.com/product-detail?id=5026555416986&categoryName=playstation4-software&superCatName=gaming&title=grand-theft-auto-v',
                        'weight' => 0.1,
                        'year' => 2014
                    ],
                    'error' => [],
                ]
            ]
        ];

        // This is the expectation
        $this->cexController->shouldReceive('getCexItemDetails')
            ->once()
            ->with($this->barcode)
            ->andReturn(new JsonResponse($expectedData, Response::HTTP_OK));

        // Make the mock call
        $response = $this->cexController->getCexItemDetails($this->barcode);

        // Check the assertions
        // Use getData(true) to get the underlying data (array) instead of the JsonResponse object
        $this->assertEquals($expectedData, $response->getData(true));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('ack', $response->getData(true)['response']);

    }

    // This test is the same as the one above but without the barcode
    public function test_get_cex_item_details_without_barcode()
    {
        // Set up some mock data
        $expectedData = [
            'response' => [
                'ack' => 'Failure',
                'data' => '',
                'error' => [
                    'code' => 12,
                    'internal_message' => 'Service not found',
                    'moreInfo' => []
                ],
                'status' => 400,
            ]
        ];

        // This is the expectation
        $this->cexController->shouldReceive('getCexItemDetails')
            ->once()
            ->with(null)
            ->andReturn(new JsonResponse($expectedData, Response::HTTP_BAD_REQUEST));

        // Make the mock call
        $response = $this->cexController->getCexItemDetails(null);

        // Check the assertions
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertArrayHasKey('error', $response->getData(true)['response']);
    }

}
