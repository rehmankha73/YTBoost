<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_type',
        'channel_url',
        'is_state_busy',
        'player_id',
        'required_count',
        'required_time',
        'user_id',
        'video_url',
    ];

    protected $casts = [
        "is_state_busy" => "boolean"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participants::class);
    }
}
