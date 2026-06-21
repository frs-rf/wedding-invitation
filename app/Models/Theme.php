<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Katalog tema undangan (global, lintas tenant).
 *
 * @property string     $theme_name
 * @property string     $view_path   Path komponen Vue per tema
 * @property bool       $is_active
 */
class Theme extends Model
{
    /** @use HasFactory<\Database\Factories\ThemeFactory> */
    use HasFactory;

    protected $fillable = [
        'theme_name',
        'view_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Semua undangan yang memakai tema ini.
     *
     * @return HasMany<Wedding, $this>
     */
    public function weddings(): HasMany
    {
        return $this->hasMany(Wedding::class);
    }

    /**
     * Scope: hanya tema yang aktif (siap dipakai pengantin).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
