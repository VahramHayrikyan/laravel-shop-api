<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Flight extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function asdf()
    {
        DB::class;
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
