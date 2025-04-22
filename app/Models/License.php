<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_key',
        'client_name',
        'product_id',
        'domain',
        'ip_address',
        'is_active',
        'is_permanent',
        'activated_at',
        'expires_at',
        'activation_code',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_permanent' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(LicenseLog::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public static function generateLicenseKey(): string
    {
        return strtoupper(Str::random(4) . '-' . Str::random(4) . '-' .
                          Str::random(4) . '-' . Str::random(4));
    }

    public static function generateActivationCode(): string
    {
        return Str::upper(Str::random(32));
    }

    public function isExpired(): bool
    {
        if ($this->is_permanent) {
            return false;
        }

        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }
}
