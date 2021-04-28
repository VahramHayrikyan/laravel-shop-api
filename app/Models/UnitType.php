<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function unitTypeValues(): HasMany
    {
        return $this->hasMany(UnitTypeValue::class);
    }
}
