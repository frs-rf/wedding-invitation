<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder akun dummy untuk testing development.
 *
 * Password semua akun: password
 *
 * Relasi:
 *   Vendor <-- Pengantin (vendor_id)
 *   Vendor <-- Resepsionis (vendor_id)
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => $password,
            'role' => Role::SuperAdmin,
            'vendor_id' => null,
        ]);

        // 2. Vendor (WO)
        $vendor = User::create([
            'name' => 'PT. Maju Bersama Wedding',
            'email' => 'vendor@test.com',
            'password' => $password,
            'role' => Role::Vendor,
            'vendor_id' => null,
        ]);

        // 3. Pengantin (klien di bawah Vendor)
        User::create([
            'name' => 'Budi Santoso & Riri Amelia',
            'email' => 'pengantin@test.com',
            'password' => $password,
            'role' => Role::Pengantin,
            'vendor_id' => $vendor->id,
        ]);

        // 4. Resepsionis (akses via magic link, tapi tetap punya akun)
        User::create([
            'name' => 'Sari - Resepsionis Lapangan',
            'email' => 'receptionist@test.com',
            'password' => $password,
            'role' => Role::Receptionist,
            'vendor_id' => $vendor->id,
        ]);

        $this->command->info('4 akun dummy berhasil dibuat (password: password)');
        $this->command->table(
            ['Role', 'Email', 'Nama'],
            User::all()->map(fn (User $u) => [
                $u->role->label(),
                $u->email,
                $u->name,
            ]),
        );
    }
}
