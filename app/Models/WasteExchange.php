<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteExchange extends Model
{
    protected $fillable = [
        'textile_waste_id',
        'supplier_company_id',
        'receiver_company_id',
        'quantity',
        'status',
        'exchange_date',
        'notes',
        'final_price',
        'exchange_details'
    ];

    protected $casts = [
        'exchange_date' => 'datetime',
        'exchange_details' => 'array',
        'quantity' => 'decimal:2',
        'final_price' => 'decimal:2'
    ];

    /**
     * Get the textile waste associated with the exchange.
     */
    public function textileWaste(): BelongsTo
    {
        return $this->belongsTo(TextileWaste::class);
    }

    /**
     * Get the supplier company.
     */
    public function supplierCompany(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'supplier_company_id');
    }

    /**
     * Get the receiver company.
     */
    public function receiverCompany(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'receiver_company_id');
    }

    /**
     * Check if the exchange is in progress.
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, ['requested', 'accepted']);
    }

    /**
     * Check if the exchange is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the exchange is cancelled.
     */
    public function isCancelled(): bool
    {
       return $this->status === 'cancelled';
    }
}


