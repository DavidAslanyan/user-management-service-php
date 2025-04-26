<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    protected $keyType = 'string'; 
    public $incrementing = false;

    protected $fillable = [
        'device_identifier',
        'device_name',
        'device_location',
        'last_ip_address',
        'last_activity',
        'push_notification_token',
        'app_version',
        'is_device_active',
        'device_type',
        'last_login',
    ];

    protected $casts = [
        'is_device_active' => 'boolean',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function accessTokens(): HasMany
    {
        return $this->hasMany(AccessToken::class, 'device_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_devices', 'device_id', 'user_id');
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class, 'device_id');
    }
}
