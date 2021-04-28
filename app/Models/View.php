<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class View extends Model
{
    protected $fillable = [
        'product_id',
        'print_area_type_id',
        'file_id',
        'name',
        'is_default',
        'display_order',
        'width_in',
        'height_in',
        'width_px',
        'height_px',
        'left_px',
        'top_px',
        'left_in',
        'top_in',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function printAreaType(): BelongsTo
    {
        return $this->belongsTo(PrintAreaType::class);
    }

    public function viewFiles(): HasMany
    {
        return $this->hasMany(ViewFile::class);
    }
}
