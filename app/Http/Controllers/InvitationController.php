<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function show(Request $request, $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        
        $guestToken = $request->query('guest');
        if (!$guestToken) {
            abort(403, 'Akses ditolak: Parameter tamu tidak ditemukan.');
        }

        $guest = $wedding->guests()->where('secure_token', $guestToken)->first();
        if (!$guest) {
            abort(403, 'Akses ditolak: Undangan tidak valid.');
        }

        // Generate QR Code containing the secure_token
        $qrCode = new QrCode(
            data: $guest->secure_token,
            size: 300,
            margin: 10
        );
            
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrCodeBase64 = $result->getDataUri();

        return Inertia::render('Invitation/Show', [
            'wedding' => $wedding,
            'theme' => $wedding->theme,
            'guest' => $guest,
            'qrCode' => $qrCodeBase64,
        ]);
    }
}
