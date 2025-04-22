<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_id',
        'action',
        'success',
        'ip_address',
        'user_agent',
        'request_data',
        'response_data',
    ];

    protected $casts = [
        'success' => 'boolean',
        'request_data' => 'json',
        'response_data' => 'json',
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public static function logAction($licenseId, $action, $success, $request = null, $response = null)
    {
        return self::create([
            'license_id' => $licenseId,
            'action' => $action,
            'success' => $success,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_data' => $request,
            'response_data' => $response,
        ]);
    }
}