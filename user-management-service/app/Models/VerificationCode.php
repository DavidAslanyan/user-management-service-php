<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Device;
use App\Enums\VerificationCodeStatusEnum;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'device_id', 'code', 'status', 'attempt_count', 'sent_at', 'expires_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function getStatusAttribute($value)
    {
        return VerificationCodeStatusEnum::from($value);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = VerificationCodeStatusEnum::from($value)->value;
    }
}
