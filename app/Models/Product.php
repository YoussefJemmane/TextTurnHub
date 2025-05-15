<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'artisan_profile_id',
        'name',
        'description',
        'category',
        'price',
        'stock',
        'unit',
        'color',
        'material',
        'image',
        'sales_count',
        'is_featured',
    ];

    
    public function artisanProfile()
    {
        return $this->belongsTo(ArtisanProfile::class);
    }

   


}
