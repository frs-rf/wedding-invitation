<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

/**
 * Seeder contoh data wedding + guests untuk MVP.
 *
 * Membuat:
 *   - 1 wedding (terhubung ke pengantin budi-riri)
 *   - 8 guest (beberapa VIP, semua punya secure_token unik)
 *
 * @see PROGRESS.md Fase 2
 */
class WeddingGuestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil user pengantin
        $pengantin = User::where('email', 'pengantin@test.com')->firstOrFail();

        // 2. Buat wedding
        $wedding = Wedding::create([
            'user_id' => $pengantin->id,
            'theme_id' => 1, // Floral Classic
            'slug' => 'budi-riri',
            'theme_data' => [
                'bride_name' => 'Riri Amelia',
                'groom_name' => 'Budi Santoso',
                'event_date' => '2025-09-20',
                'event_time' => '10:00',
                'venue_name' => 'The Glass House',
                'venue_address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'gmap_link' => 'https://maps.google.com/?q=The+Glass+House+Jakarta',
                'love_story' => 'Kami bertemu di kampus tahun 2018...',
                'gallery' => [],
                'bank_accounts' => [
                    ['bank' => 'BCA', 'number' => '1234567890', 'holder' => 'Budi Santoso'],
                    ['bank' => 'Mandiri', 'number' => '0987654321', 'holder' => 'Riri Amelia'],
                ],
            ],
            'event_date' => '2025-09-20',
        ]);

        // Generate receptionist token (untuk test Fase 6 nanti)
        $receptionistToken = $wedding->generateReceptionistToken();
        $this->command->info("Receptionist token: {$receptionistToken}");

        // 3. Buat 8 guest dummy
        $guests = [
            ['name' => 'Ir. Ahmad Fauzi', 'whatsapp_number' => '6281234567890', 'is_vip' => true, 'company_or_address' => 'PT. Global Tech', 'relationship' => 'Sahabat Budi'],
            ['name' => 'Dr. Siti Rahayu', 'whatsapp_number' => '6281345678901', 'is_vip' => true, 'company_or_address' => null, 'relationship' => 'Sahabat Riri'],
            ['name' => 'Andi Wijaya', 'whatsapp_number' => '6281456789012', 'is_vip' => false, 'company_or_address' => 'CV. Maju Sejahtera', 'relationship' => 'Teman Kuliah'],
            ['name' => 'Lisa Permata Sari', 'whatsapp_number' => '6281567890123', 'is_vip' => true, 'company_or_address' => null, 'relationship' => 'Adik Riri'],
            ['name' => 'Rudi Hermawan', 'whatsapp_number' => '6281678901234', 'is_vip' => false, 'company_or_address' => 'PT. Indo Makmur', 'relationship' => 'Rekan Kerja'],
            ['name' => 'Maya Anggraini', 'whatsapp_number' => '6281789012345', 'is_vip' => false, 'company_or_address' => null, 'relationship' => 'Teman SMA'],
            ['name' => 'Bapak Hendra Santoso', 'whatsapp_number' => '6281890123456', 'is_vip' => true, 'company_or_address' => null, 'relationship' => 'Paman Budi'],
            ['name' => 'Dewi Lestari', 'whatsapp_number' => '6281901234567', 'is_vip' => false, 'company_or_address' => 'Universitas Indonesia', 'relationship' => 'Dosen'],
        ];

        foreach ($guests as $guestData) {
            Guest::create(array_merge($guestData, [
                'wedding_id' => $wedding->id,
            ]));
        }

        $this->command->info('1 wedding + 8 guest berhasil dibuat.');
        $this->command->table(
            ['Nama', 'WA', 'VIP', 'Relasi'],
            $wedding->guests->map(fn (Guest $g) => [
                $g->name,
                $g->whatsapp_number ?? '-',
                $g->is_vip ? '⭐' : '-',
                $g->relationship ?? '-',
            ]),
        );
    }
}
