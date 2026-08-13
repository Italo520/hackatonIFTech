<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
use App\Models\Atrativo;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Resolve um QR code escaneado
     */
    public function resolve($hash)
    {
        // Na prática, buscaria no banco:
        // $qr = QrCodeModel::where('hash_code', $hash)->firstOrFail();
        // Incrementa leituras
        // $qr->increment('leituras');
        
        // Simulação para o MVP
        return redirect()->route('pwa.atrativo', ['id' => 1])
            ->with('message', 'QR Code de check-in lido com sucesso!');
    }
}
