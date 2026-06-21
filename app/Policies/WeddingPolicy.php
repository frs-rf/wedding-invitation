<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use App\Models\Wedding;

/**
 * Policy tenant isolation untuk model Wedding.
 *
 * Aturan akses:
 *   - Super Admin : bebas akses semua wedding
 *   - Vendor      : akses wedding milik klien binaannya (vendor_id)
 *   - Pengantin   : hanya akses wedding miliknya sendiri (user_id)
 *   - Resepsionis : tidak punya akses dashboard (sudah diblokir di route)
 *
 * @see PRD Section 2 — RBAC & NFR — Tenant Isolation
 */
class WeddingPolicy
{
    /**
     * Siapa saja yang login & ber-peran dashboard user bisa lihat daftar wedding
     * (controller akan memfilter lebih lanjut berdasarkan role).
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [Role::SuperAdmin, Role::Vendor, Role::Pengantin], true);
    }

    public function view(User $user, Wedding $wedding): bool
    {
        return $this->canAccess($user, $wedding);
    }

    /**
     * Hanya Pengantin yang boleh membuat wedding (1 per akun defaultnya).
     * Vendor bisa membuat wedding ATAS nama klien — lewat controller khusus.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [Role::SuperAdmin, Role::Vendor, Role::Pengantin], true);
    }

    public function update(User $user, Wedding $wedding): bool
    {
        return $this->canAccess($user, $wedding);
    }

    public function delete(User $user, Wedding $wedding): bool
    {
        return $this->canAccess($user, $wedding);
    }

    /**
     * Cek apakah user berhak mengakses wedding ini.
     * Resepsionis di-exclude eksplisit (tidak boleh via dashboard).
     */
    private function canAccess(User $user, Wedding $wedding): bool
    {
        return match ($user->role) {
            Role::SuperAdmin => true,
            // Pengantin hanya untuk wedding miliknya.
            Role::Pengantin => $wedding->user_id === $user->id,
            // Vendor hanya untuk wedding milik klien binaannya.
            Role::Vendor => $wedding->user->vendor_id === $user->id,
            Role::Receptionist => false,
        };
    }
}
