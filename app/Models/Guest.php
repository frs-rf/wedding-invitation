<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Tamu undangan untuk satu wedding.
 *
 * @property int               $wedding_id
 * @property string            $name
 * @property string|null       $whatsapp_number
 * @property bool              $is_vip
 * @property string|null       $company_or_address
 * @property string|null       $relationship
 * @property string            $secure_token    32-byte hex (jangan ekspos id)
 * @property bool              $is_checked_in
 * @property \Carbon\Carbon|null $checked_in_at
 */
class Guest extends Model
{
    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'name',
        'whatsapp_number',
        'is_vip',
        'company_or_address',
        'relationship',
        'secure_token',
        'is_checked_in',
        'checked_in_at',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Boot: generate secure_token otomatis saat membuat guest baru
     * bila belum diisi secara eksplisit.
     */
    protected static function booted(): void
    {
        static::creating(function (Guest $guest) {
            $guest->secure_token ??= self::generateToken();
        });
    }

    /**
     * Generate token kriptografi baru (32-byte hex = 64 karakter).
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Undangan tempat tamu ini terdaftar.
     *
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
