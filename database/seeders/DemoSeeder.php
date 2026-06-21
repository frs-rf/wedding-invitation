<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guest;
use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $pengantin = User::where('email', 'pengantin@test.com')->first();
        if (!$pengantin) {
            $this->command->error("User pengantin@test.com tidak ditemukan. Jalankan db:seed dulu jika kosong.");
            return;
        }

        $theme = Theme::firstOrCreate(
            ['theme_name' => 'Elegant Glassmorphism'],
            ['view_path' => 'ElegantGlass', 'is_active' => true]
        );

        $wedding = $pengantin->weddings()->first();
        if (!$wedding) {
            $wedding = $pengantin->weddings()->create([
                'slug' => 'budi-riri-' . Str::random(4)
            ]);
        } else {
            $wedding->update(['slug' => 'budi-riri']);
        }

        $wedding->update([
            'theme_id' => $theme->id,
            'event_date' => now()->addDays(14)->toDateString(),
            'theme_data' => [
                'bride_name' => 'Budi Santoso',
                'groom_name' => 'Riri Andayani',
                'love_story' => 'Dari teman sebangku hingga menjadi teman hidup, kami mengundang Anda untuk merayakan hari paling bahagia dalam hidup kami.',
                'venue_name' => 'The Ritz-Carlton Grand Ballroom',
                'venue_address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan',
                'gmap_link' => 'https://maps.google.com'
            ]
        ]);

        $receptionistToken = $wedding->generateReceptionistToken();

        // Bersihkan tamu lama
        $wedding->guests()->delete();

        // Buat Tamu
        $guestsData = [
            ['name' => 'Bapak Presiden RI', 'whatsapp_number' => '08123456789', 'is_vip' => true, 'company_or_address' => 'Istana Negara', 'relationship' => 'VVIP Guest'],
            ['name' => 'Anya Geraldine', 'whatsapp_number' => '08198765432', 'is_vip' => true, 'company_or_address' => 'Artis', 'relationship' => 'Sahabat Pengantin'],
            ['name' => 'Reza Rahadian', 'whatsapp_number' => '08111222333', 'is_vip' => false, 'company_or_address' => 'Aktor', 'relationship' => 'Rekan Kerja'],
            ['name' => 'Dian Sastrowardoyo', 'whatsapp_number' => '08133344455', 'is_vip' => false, 'company_or_address' => 'Sahabat Lama', 'relationship' => 'Teman SMA'],
        ];

        $guests = [];
        foreach ($guestsData as $g) {
            $guests[] = $wedding->guests()->create($g);
        }

        $this->command->info('✅ Data Dummy Pengantin & Tamu berhasil di-generate!');
        $this->command->info('===================================================================');
        $this->command->info('Link Undangan Tamu (Pilih salah satu):');
        foreach ($guests as $g) {
            $this->command->line("- {$g->name}: http://localhost:8000/invitation/{$wedding->slug}?guest={$g->secure_token}");
        }
        $this->command->info('===================================================================');
        $this->command->info('Link Scanner Resepsionis (Tanpa perlu login):');
        $this->command->line("http://localhost:8000/check-in/{$wedding->slug}?token={$receptionistToken}");
        $this->command->info('===================================================================');
    }
}
