<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Unit pernikahan / undangan (1 row = 1 undangan).
 *
 * @property int               $user_id           ID pengantin pemilik
 * @property int|null          $theme_id
 * @property string            $slug
 * @property array|null        $theme_data        Konten dinamis tema (JSONB)
 * @property string|null       $receptionist_token
 * @property \Carbon\Carbon|null $event_date
 */
class Wedding extends Model
{
    /** @use HasFactory<\Database\Factories\WeddingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme_id',
        'slug',
        'theme_data',
        'receptionist_token',
        'event_date',
    ];

    protected $casts = [
        'theme_data' => 'array',
        'event_date' => 'date',
        'receptionist_token' => 'encrypted',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Pengantin pemilik undangan.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tema yang dipilih untuk undangan ini.
     *
     * @return BelongsTo<Theme, $this>
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Daftar tamu undangan.
     *
     * @return HasMany<Guest, $this>
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    /**
     * Generate & simpan token resepsionis (magic link).
     * Mengembalikan token plain-text HANYA sekali (untuk ditampilkan ke pengantin).
     */
    public function generateReceptionistToken(): string
    {
        $token = Str::random(64);

        $this->forceFill([
            'receptionist_token' => $token,
        ])->save();

        return $token;
    }

    /**
     * Hitung jumlah tamu yang sudah check-in.
     */
    public function checkedInGuestsCount(): int
    {
        return $this->guests()->where('is_checked_in', true)->count();
    }
}
