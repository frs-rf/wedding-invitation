<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel katalog tema undangan (global, lintas tenant).
 *
 * @see PRD Section 4 — Tabel themes
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('theme_name', 100);
            // Path komponen Vue yang merender tema, mis. 'Themes/FloralClassic'.
            $table->string('view_path', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
