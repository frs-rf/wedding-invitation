<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckInController extends Controller
{
    public function index(Request $request, $slug)
    {
        $token = $request->query('token');
        if (!$token) {
            abort(403, 'Token resepsionis tidak ditemukan.');
        }

        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        if ($wedding->receptionist_token !== $token) {
            abort(403, 'Token resepsionis tidak valid.');
        }

        return Inertia::render('Receptionist/Scanner', [
            'wedding' => $wedding,
            'token' => $token,
        ]);
    }

    public function process(Request $request, $slug)
    {
        $request->validate([
            'token' => 'required|string',
            'guest_token' => 'required|string',
        ]);

        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        if ($wedding->receptionist_token !== $request->token) {
            return response()->json(['error' => 'Akses resepsionis tidak valid.'], 403);
        }

        $guest = $wedding->guests()->where('secure_token', $request->guest_token)->first();

        if (!$guest) {
            return response()->json(['error' => 'Tamu tidak ditemukan atau tiket palsu.'], 404);
        }

        if ($guest->is_checked_in) {
            return response()->json(['error' => 'Tamu sudah melakukan check-in sebelumnya.'], 400);
        }

        $guest->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil untuk tamu: ' . $guest->name,
            'guest' => $guest,
        ]);
    }
}
