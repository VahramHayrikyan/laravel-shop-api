<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeGuide extends Model
{
    protected $fillable = [
        'name',
        'description',
        'size_guide_infographic_id',
        'is_active',
    ];
}
