/**
 * Tipe shared untuk Role user di frontend.
 * Wajib sinkron dengan backend enum App\Enums\Role.
 *
 * @see /app/Enums/Role.php
 */
export type Role = 1 | 2 | 3 | 4

export const ROLE_LABELS: Record<Role, string> = {
  1: 'Super Admin',
  2: 'Vendor (WO)',
  3: 'Pengantin',
  4: 'Resepsionis',
}
