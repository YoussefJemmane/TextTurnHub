<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtisanProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'specialty',
        'experience_level',
        'materials_interest',
    ];

    /**
     * Get the user that owns the artisan profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
