<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'view_id',
        'attribute_value_id',
        'file_id',
    ];

    public function view(): BelongsTo
    {
        return $this->belongsTo(View::class);
    }

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
