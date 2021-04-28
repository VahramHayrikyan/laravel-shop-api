<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitTypeValue extends Model
{
    protected $fillable = [
        'unit_type_id',
        'name',
        'code',
        'description',
        'display_order',
        'is_active',
    ];

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }
}
