<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return match ($user->role) {
            Role::SuperAdmin => $this->superadminDashboard($user),
            Role::Vendor => redirect()->route('vendor.clients.index'),
            Role::Pengantin => $this->pengantinDashboard($user),
            Role::Receptionist => abort(403, 'Resepsionis tidak memiliki dashboard. Gunakan magic link yang diberikan.'),
        };
    }

    private function superadminDashboard($user)
    {
        $totalVendors = DB::table('users')->where('role', Role::Vendor->value)->count();
        $totalPengantin = DB::table('users')->where('role', Role::Pengantin->value)->count();
        $totalGuests = DB::table('guests')->count();

        $allUsers = \App\Models\User::whereIn('role', [Role::Vendor, Role::Pengantin])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => [
                'total_vendors' => $totalVendors,
                'total_pengantin' => $totalPengantin,
                'total_guests' => $totalGuests,
            ],
            'users' => $allUsers,
        ]);
    }

    private function vendorDashboard($user)
    {
        // This is no longer used, as vendor is redirected directly to clients.index
        $activeClients = $user->clients()->count();
        
        $totalGuests = DB::table('guests')
            ->join('weddings', 'guests.wedding_id', '=', 'weddings.id')
            ->join('users', 'weddings.user_id', '=', 'users.id')
            ->where('users.vendor_id', $user->id)
            ->count();

        return Inertia::render('Vendor/Dashboard', [
            'stats' => [
                'active_clients' => $activeClients,
                'total_guests' => $totalGuests,
            ],
        ]);
    }

    private function pengantinDashboard($user)
    {
        $totalGuests = 0;
        $checkedIn = 0;

        $wedding = $user->weddings()->first();
        if ($wedding) {
            $totalGuests = $wedding->guests()->count();
            $checkedIn = $wedding->guests()->where('is_checked_in', true)->count();
        }

        return Inertia::render('Pengantin/Dashboard', [
            'stats' => [
                'total_guests' => $totalGuests,
                'checked_in' => $checkedIn,
            ],
            'wedding' => $wedding,
        ]);
    }

    public function generateToken(Request $request)
    {
        $user = $request->user();
        $wedding = $user->weddings()->first();
        if ($wedding) {
            $wedding->generateReceptionistToken();
        }

        return redirect()->back()->with('success', 'Token Resepsionis berhasil dibuat.');
    }
}
