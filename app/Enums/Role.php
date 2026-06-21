<?php

namespace App\Enums;

/**
 * Peran pengguna dalam sistem Wedding Invitation SaaS.
 *
 * Hierarki akses (paling tinggi → terendah):
 *   SuperAdmin > Vendor (WO) > Pengantin > Resepsionis
 *
 * Catatan: "Tamu Undangan" tidak termasuk role karena tidak punya akun
 * (hanya akses via tokenized URL, lihat PRD Section 2).
 *
 * @see PRD Section 2 — Arsitektur & Peran Pengguna (RBAC)
 */
enum Role: int
{
    case SuperAdmin = 1;
    case Vendor = 2;
    case Pengantin = 3;
    case Receptionist = 4;

    /**
     * Label ramah-manusia untuk ditampilkan di UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Vendor => 'Vendor (WO)',
            self::Pengantin => 'Pengantin',
            self::Receptionist => 'Resepsionis',
        };
    }

    /**
     * Daftar semua nilai integer untuk validasi/migration.
     *
     * @return int[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Cek apakah role ini termasuk "dashboard user" (punya akses panel dashboard).
     * Resepsionis tidak punya dashboard (hanya halaman scanner via magic link).
     */
    public function isDashboardUser(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Vendor, self::Pengantin], true);
    }
}
