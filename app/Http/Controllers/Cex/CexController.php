<?php

namespace App\Http\Controllers\Cex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            $response = Http::withUserAgent($this->userAgent)
                        ->get($this->baseCexUrl . '/' . $barcode . '/detail');

            return $response->json();

        }catch(\Exception $e) {
            Log::error('Cex error ' . $e->getMessage());
            return [
                'errors' => $e->getMessage()
            ];
        }
    }
}
