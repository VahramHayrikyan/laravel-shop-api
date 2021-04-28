<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKind extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    public static function getKindByCode($code)
    {
        return self::where('code', $code)->first();
    }
}
