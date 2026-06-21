<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware proteksi route berdasarkan role user.
 *
 * Usage di route:
 *   Route::middleware(['auth', 'role:super_admin'])->group(...);
 *   Route::middleware(['auth', 'role:vendor,pengantin'])->group(...);
 *
 * Implementasi: pisah role parameter by koma, kemudian cek apakah
 * role user ada di whitelist. Jika tidak, abort 403.
 *
 * @see PRD Section 2 — RBAC
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$allowedRoles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Cast role string dari DB ke enum (fallback aman bila value invalid)
        try {
            $userRole = Role::from($user->role);
        } catch (\ValueError $e) {
            abort(403, 'Role pengguna tidak valid.');
        }

        $allowed = array_map(
            static fn (string $r) => Role::from(trim($r)),
            $allowedRoles,
        );

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
