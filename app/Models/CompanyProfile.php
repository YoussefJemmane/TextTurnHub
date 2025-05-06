<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CompanyProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'company_size',
        'waste_types',
    ];

    /**
     * Get the user that owns the company profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
