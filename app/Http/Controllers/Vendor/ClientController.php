<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wedding;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::where('vendor_id', $request->user()->id)
            ->with(['weddings' => function($query) {
                $query->withCount('guests');
            }])
            ->get();

        return Inertia::render('Vendor/Clients/Index', [
            'clients' => $clients
        ]);
    }

    public function create()
    {
        return Inertia::render('Vendor/Clients/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'slug' => 'required|string|max:255|unique:weddings',
            'event_date' => 'required|date',
        ]);

        $pengantin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::Pengantin,
            'vendor_id' => $request->user()->id,
        ]);

        Wedding::create([
            'user_id' => $pengantin->id,
            'slug' => $request->slug,
            'event_date' => $request->event_date,
            'theme_data' => [],
        ]);

        return redirect()->route('vendor.clients.index')->with('success', 'Klien pengantin berhasil dibuat.');
    }

    public function impersonate(User $client)
    {
        if ($client->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $vendorId = auth()->id();
        auth()->login($client);
        session(['impersonated_by' => $vendorId]);

        return redirect()->route('dashboard')->with('success', 'Berhasil login sebagai Pengantin.');
    }
}
