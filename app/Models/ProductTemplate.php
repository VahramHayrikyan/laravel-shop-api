<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTemplate extends Model
{
    protected $fillable = [
        'product_type_id',
        'name',
        'description',
        'file_id',
        'blue_mask_file_id',
        'red_mask_file_id',
        'width_in',
        'height_in',
        'is_active',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
