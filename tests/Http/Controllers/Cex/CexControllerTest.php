<?php

namespace Tests\Http\Controllers\Cex;


use App\Http\Controllers\Cex\CexController;
use Illuminate\Http\Response;
use Tests\TestCase;

class CexControllerTest extends TestCase
{

    protected String $barcode;
    protected function setUp(): void
    {
        parent::setUp();

        $this->cexController = app()[CexController::class];

        // GTA 5 PS4 Barcode
        $this->barcode = '5026555416986';
    }

    public function test_get_cex_item_details_with_barcode()
    {
        $response = $this->cexController->getCexItemDetails($this->barcode);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertNotEmpty($response);
        $this->assertIsArray(json_decode($response->getContent(), true));
        $this->assertArrayHasKey('response',json_decode($response->getContent(), true));
        $this->assertArrayHasKey('data', json_decode($response->getContent(), true)['response']);
    }

    public function test_get_cex_item_details_without_barcode()
    {
        $response = $this->cexController->getCexItemDetails(null);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertArrayHasKey('error', json_decode($response->getContent(), true)['response']);
    }

}
