<?php

namespace App\Http\Controllers\Cex;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class CexController extends Controller
{
    private $baseCexUrl = null;
    protected $userAgent = null;
    
    public function __construct()
    {
        $this->baseCexUrl = env('CEX_API_DETAILS_URL');
        $this->userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0';
    }

    public function getCexItemDetails($barcode = null): array
    {
        try{  
            // If we have a barcode send the request
            if(!empty($barcode)){
                $response = Http::withUserAgent($this->userAgent)
                                ->get($this->baseCexUrl . '/' . $barcode . '/detail');

                return $response->json();
            }

            // No barcode fail and send errors back
            Log::error('Cex error -  No barcode sent');
            return [
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
            ];
            
        }catch(\Exception $e) {
            Log::error('Cex error - ' . $e->getMessage());
            return [
                'errors' => $e->getMessage()
            ];
        }
    }
}
