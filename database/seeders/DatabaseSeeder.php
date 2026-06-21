<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan penting: User dulu, lalu theme, lalu wedding+guest.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ThemeSeeder::class,
            WeddingGuestSeeder::class,
        ]);
    }
}
