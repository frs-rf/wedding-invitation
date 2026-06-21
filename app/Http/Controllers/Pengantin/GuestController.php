<?php

namespace App\Http\Controllers\Pengantin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Imports\GuestsImport;
use App\Exports\GuestTemplateExport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $wedding = $request->user()->weddings()->first();
        if (!$wedding) {
            abort(404, 'Data pernikahan tidak ditemukan.');
        }

        $query = $wedding->guests();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->has('filter') && $request->filter != 'all') {
            if ($request->filter == 'vip') {
                $query->where('is_vip', true);
            } elseif ($request->filter == 'regular') {
                $query->where('is_vip', false);
            }
        }

        $guests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return Inertia::render('Pengantin/Guest/Index', [
            'wedding' => $wedding,
            'guests' => $guests,
            'filters' => $request->only(['search', 'filter']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_vip' => 'boolean',
            'company_or_address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
        ]);

        $wedding = $request->user()->weddings()->first();

        $wedding->guests()->create($request->all());

        return back()->with('success', 'Tamu berhasil ditambahkan.');
    }

    public function update(Request $request, Guest $guest)
    {
        $wedding = $request->user()->weddings()->first();
        if ($guest->wedding_id !== $wedding->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_vip' => 'boolean',
            'company_or_address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
        ]);

        $guest->update($request->all());

        return back()->with('success', 'Data tamu berhasil diubah.');
    }

    public function destroy(Request $request, Guest $guest)
    {
        $wedding = $request->user()->weddings()->first();
        if ($guest->wedding_id !== $wedding->id) {
            abort(403);
        }

        $guest->delete();

        return back()->with('success', 'Tamu berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new GuestTemplateExport, 'template_tamu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // max 5MB
        ]);

        $wedding = $request->user()->weddings()->first();

        Excel::import(new GuestsImport($wedding->id), $request->file('file'));

        return back()->with('success', 'Proses import sedang berjalan di latar belakang.');
    }
}
