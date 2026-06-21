<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

/**
 * Seeder 3 tema contoh untuk MVP.
 */
class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'theme_name' => 'Floral Classic',
                'view_path' => 'Themes/FloralClassic',
                'is_active' => true,
            ],
            [
                'theme_name' => 'Modern Minimal',
                'view_path' => 'Themes/ModernMinimal',
                'is_active' => true,
            ],
            [
                'theme_name' => 'Garden Party',
                'view_path' => 'Themes/GardenParty',
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }

        $this->command->info('3 tema berhasil dibuat.');
    }
}
