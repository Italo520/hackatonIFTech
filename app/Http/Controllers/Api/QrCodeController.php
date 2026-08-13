<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;

class QrCodeController extends Controller
{
    public function scan(string $hash)
    {
        $qr = QrCode::with('atrativo')->where('hash_code', $hash)->firstOrFail();

        // Register metric
        $qr->increment('scans');

        return response()->json(['atrativo' => $qr->atrativo]);
    }
}
