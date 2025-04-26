<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class B2BProfile extends Model
{
    use HasFactory;

    protected $table = 'b2b_profile';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'account_image_path',
        'profile_type',
        'profile_role',
        'profile_image_path',
        'legal_name',
        'venue_name',
        'stage_name',
        'genres',
        'website',
        'facebook',
        'instagram',
        'linkedin',
        'portfolio_images',
        'portfolio_videos',
        'youtube_links',
        'cover_photo_path',
        'bio',
        'profile_status',
        'address',
        'rejected_reason',
        'follower_count',
    ];

    protected $casts = [
        'genres' => 'array',
        'portfolio_images' => 'array',
        'portfolio_videos' => 'array',
        'youtube_links' => 'array',
        'follower_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            B2CProfile::class,
            'follower',
            'b2b_profile_id',
            'b2c_profile_id'
        );
    }
}
