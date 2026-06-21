<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel daftar tamu per pernikahan.
 *
 * Kolom secure_token WAJIB unik & kriptografi — dipakai di URL publik
 * undangan tamu & QR code check-in. Jangan ekspos id inkremental di URL. (NFR)
 *
 * @see PRD Section 4 — Tabel guests
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('name', 255);
            $table->string('whatsapp_number', 50)->nullable();
            $table->boolean('is_vip')->default(false);
            $table->text('company_or_address')->nullable();
            $table->string('relationship', 100)->nullable();

            // 32-byte hex (64 karakter) — token kriptografi untuk URL & QR.
            $table->string('secure_token', 64)->unique();

            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();

            $table->timestamps();

            // Index untuk query "daftar tamu per wedding" yang sangat sering.
            $table->index('wedding_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
