<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Guest;
use App\Models\User;
use App\Models\Wedding;

/**
 * Policy tenant isolation untuk model Guest.
 *
 * Akses guest selalu didelegasi ke WeddingPolicy —
 * user hanya boleh akses guest bila berhak mengakses wedding terkait.
 *
 * @see PRD Section 2 — RBAC & NFR — Tenant Isolation
 */
class GuestPolicy
{
    /**
     * viewAny hanya relevan di controller yang sudah scope ke wedding tertentu.
     * Controller wajib memastikan wedding yang di-query sudah authorized.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [Role::SuperAdmin, Role::Vendor, Role::Pengantin], true);
    }

    public function view(User $user, Guest $guest): bool
    {
        return $this->canAccessViaWedding($user, $guest->wedding);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [Role::SuperAdmin, Role::Vendor, Role::Pengantin], true);
    }

    public function update(User $user, Guest $guest): bool
    {
        return $this->canAccessViaWedding($user, $guest->wedding);
    }

    public function delete(User $user, Guest $guest): bool
    {
        return $this->canAccessViaWedding($user, $guest->wedding);
    }

    /**
     * Delegasi ke WeddingPolicy::canAccess().
     * User boleh akses guest bila boleh akses wedding-nya.
     */
    private function canAccessViaWedding(User $user, Wedding $wedding): bool
    {
        return match ($user->role) {
            Role::SuperAdmin => true,
            Role::Pengantin => $wedding->user_id === $user->id,
            Role::Vendor => $wedding->user->vendor_id === $user->id,
            Role::Receptionist => false,
        };
    }
}
