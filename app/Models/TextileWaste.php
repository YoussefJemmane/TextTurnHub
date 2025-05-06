<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TextileWaste extends Model
{
    protected $fillable = [
        'company_profiles_id',
        'title',
        'description',
        'waste_type',
        'material_type',
        'quantity',
        'unit',
        'condition',
        'color',
        'composition',
        'minimum_order_quantity',
        'price_per_unit',
        'location',
        'availability_status',
        'images',
        'sustainability_metrics',
    ];

    protected $casts = [
        'images' => 'array',
        'sustainability_metrics' => 'array',
        'quantity' => 'decimal:2',
        'minimum_order_quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
    ];

    /**
     * Get the company that owns the textile waste.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profiles_id');
    }

    /**
     * Get the waste exchanges for this textile waste.
     */
    public function wasteExchanges(): HasMany
    {
        return $this->hasMany(WasteExchange::class);
    }

    /**
     * Scope a query to only include available textile wastes.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    /**
     * Check if the textile waste is available for exchange.
     */
    public function isAvailable(): bool
    {
        return $this->availability_status === 'available';
    }
}
