<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProductPrintingPrice extends Model
{
    protected $fillable = [
        'supplier_id',
        'product_variant_id',
        'printing_method_id',
        'view_id',
        'price_in_cents'
    ];

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class);
    }
}
