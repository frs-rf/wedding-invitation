<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string          $name
 * @property string          $email
 * @property string          $password
 * @property string          $role           Nilai dari \App\Enums\Role
 * @property int|null        $vendor_id      ID vendor pembina (untuk Pengantin/Resepsionis)
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'vendor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'vendor_id' => 'integer',
        ];
    }

    // ============================================================
    // ROLE HELPER METHODS
    // ============================================================

    public function isSuperAdmin(): bool
    {
        return $this->role === Role::SuperAdmin;
    }

    public function isVendor(): bool
    {
        return $this->role === Role::Vendor;
    }

    public function isPengantin(): bool
    {
        return $this->role === Role::Pengantin;
    }

    public function isReceptionist(): bool
    {
        return $this->role === Role::Receptionist;
    }

    /**
     * Cek apakah user ini punya salah satu dari role yang diberikan.
     *
     * @param  Role|list<Role>  $roles
     */
    public function hasRole(Role|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($this->role, $roles, true);
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Vendor yang membina user ini (untuk role Pengantin/Resepsionis).
     * Self-referencing sesuai PRD Section 4 — kolom vendor_id di tabel users.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'vendor_id');
    }

    /**
     * Daftar klien (Pengantin) di bawah vendor ini.
     * Hanya relevan bila user adalah Vendor.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(self::class, 'vendor_id')
            ->where('role', Role::Pengantin->value);
    }

    /**
     * Daftar undangan yang dimiliki pengantin ini.
     * (Umumnya 1 pengantin = 1 wedding, tapi relasi mendukung banyak.)
     *
     * @return HasMany<Wedding, $this>
     */
    public function weddings(): HasMany
    {
        return $this->hasMany(Wedding::class);
    }
}
