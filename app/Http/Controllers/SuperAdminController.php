<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function impersonate(User $user)
    {
        if (auth()->user()->role !== Role::SuperAdmin) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === Role::SuperAdmin) {
            abort(403, 'Cannot impersonate another Super Admin.');
        }

        $superAdminId = auth()->id();
        auth()->login($user);
        session(['impersonated_by' => $superAdminId]);

        return redirect()->route('dashboard')->with('success', 'Berhasil login sebagai ' . $user->name . ' (' . $user->role->value . ').');
    }
}
