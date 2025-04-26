<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class B2CProfile extends Model
{
    protected $table = 'b2c_profile';

    protected $keyType = 'string'; 
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'loyalty_points',
        'upcoming_events_count',
        'delivery_id',
        'following_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(
            B2BProfile::class,
            'follower',
            'b2c_profile_id', 
            'b2b_profile_id'  
        );
    }
}
