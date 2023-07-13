<?php

namespace App\Http\Controllers\Cex;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class CexController extends Controller
{
    private mixed $baseCexUrl = null;
    protected ?string $userAgent = null;

    public function __construct()
    {
        $this->baseCexUrl = env('CEX_API_DETAILS_URL');
        $this->userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0';
    }

    /**
     * Api call to cex to get item information
     *
     * @param string|null $barcode
     * @return JsonResponse
     */
    public function getCexItemDetails(string $barcode = null): jsonResponse
    {
        try{
            // If we have a barcode send the request
            if(!empty($barcode)){
                $data = Http::withUserAgent($this->userAgent)
                    ->get($this->baseCexUrl . '/' . $barcode . '/detail');

                return response()->json($data->json(), Response::HTTP_OK);
            }

            // No barcode fail and send errors back
            Log::error('Cex error -  No barcode sent');
            return response()->json([
                'response' => [
                    'ack' => 'Failure',
                    'data' => '',
                    'error' => [
                        'code' => 12,
                        'internal_message' => 'Service not found',
                        'moreInfo' => []
                    ],
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            ], Response::HTTP_BAD_REQUEST);

        }catch(\Exception $e) {
            Log::error('Cex error - ' . $e->getMessage());
            return response()->json([
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
