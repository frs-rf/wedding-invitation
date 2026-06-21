<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $wedding = $request->user()->weddings()->first();
        $themes = Theme::where('is_active', true)->get();

        return Inertia::render('Pengantin/Theme/Index', [
            'wedding' => $wedding,
            'themes' => $themes,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
            'theme_data' => 'nullable|array',
        ]);

        $wedding = $request->user()->weddings()->first();
        if (!$wedding) {
            abort(404, 'Data pernikahan tidak ditemukan.');
        }

        $wedding->update([
            'theme_id' => $request->theme_id,
            'theme_data' => $request->theme_data ?? [],
        ]);

        return back()->with('success', 'Tema dan konten berhasil disimpan.');
    }
}
