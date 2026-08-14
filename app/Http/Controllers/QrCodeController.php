<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
use App\Models\Atrativo;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Resolve um QR code escaneado in loco (Totem físico do atrativo)
     */
    public function resolve($hash)
    {
        $qr = QrCodeModel::where('hash_code', $hash)->first();
        if ($qr) {
            $qr->increment('scans');
            return redirect()->route('pwa.atrativo', ['id' => $qr->atrativo_id])
                ->with('message', 'QR Code do totem validado! Bem-vindo ao local.');
        }

        if (is_numeric($hash)) {
            $atrativo = Atrativo::find($hash);
            if ($atrativo) {
                return redirect()->route('pwa.atrativo', ['id' => $hash]);
            }
        }

        return redirect()->route('pwa.explorar')->with('message', 'Ponto turístico identificado com sucesso.');
    }
}
