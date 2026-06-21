<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel unit pernikahan (1 row = 1 undangan pernikahan).
 *
 * Multi-tenant: kolom user_id menentukan kepemilikan (pengantin).
 * Vendor dapat melihat semua wedding milik klien binaannya.
 *
 * @see PRD Section 4 — Tabel weddings
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();

            // Pengantin pemilik undangan ini.
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Tema yang dipilih (boleh null bila tema dihapus).
            $table->foreignId('theme_id')
                ->nullable()
                ->constrained('themes')
                ->nullOnDelete();

            // Bagian URL publik undangan, mis. 'budi-riri'.
            $table->string('slug', 255)->unique();

            // Konten dinamis tema (nama, tanggal, lokasi, foto, rekening, dll).
            // JSONB = optimasi query di PostgreSQL.
            $table->jsonb('theme_data')->nullable();

            // Magic link resepsionis — null sampai pengantin generate.
            // 500 karakter karena di-cast 'encrypted' (Laravel encrypt menghasilkan ~200+ chars).
            $table->string('receptionist_token', 500)
                ->nullable()
                ->unique();

            $table->date('event_date')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('theme_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
