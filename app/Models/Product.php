<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_type_id', 'brand_id', 'product_kind_id',
        'care_detail_id', 'base_attribute_id', 'size_guide_id',
        'weight_unit_id', 'dimension_unit_id',
        'name', 'short_name',
        'slug', 'sku',
        'description', 'short_description',
        'feature_img', 'display_order',
        'is_active', 'is_premium', 'is_featured',
        'size_guide', 'size_guide_infographic',
        'key_features', 'feature_img_id',
        'meta_title', 'meta_description', 'meta_keyword',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'base_attribute_id' => 'int',
        'product_type_id' => 'int',
        'brand_id' => 'int',
        'is_active' => 'int',
        'display_order' => 'int',
        'is_featured' => 'int',
        'is_premium' => 'int'
    ];

    protected $hidden = ['pivot'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {

            $views = $product->views;
            foreach ($views as $view) {
                $view->viewFiles()->delete();
            }

            $product->tags()->detach();
            $product->categories()->detach();
            $product->variants()->delete();
            $product->printingMethods()->detach();
            $product->views()->delete();
        });
    }

    //------------- - relations - --------------
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function printingMethods(): BelongsToMany
    {
        return $this->belongsToMany(PrintingMethod::class)
            ->withTimestamps()->withPivot('time_estimate');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function careDetail(): BelongsTo
    {
        return $this->belongsTo(CareDetail::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_value');
    }

    public function baseAttribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'base_attribute_id');
    }

    //------------- - /relations - --------------

}
