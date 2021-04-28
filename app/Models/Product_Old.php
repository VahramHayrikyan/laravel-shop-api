<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
//use App\Http\Resources\ProductTemplate as ProductTemplateResource;

class Product_Old extends Model
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
        'key_features',
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

//    protected $attributes = [
//        'is_mask' => 0,
//    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            $views = $product->views;
            foreach ($views as $view) {
                $view->viewFile()->forceDelete();
                $view->productTemplates()->detach();
            }
            $product->categories()->detach();
            $product->mainVariants()->forceDelete();
            $product->productVariantValue()->forceDelete();
            $product->catalogProductPrintingMethods()->forceDelete();
            $product->views()->forceDelete();
            $product->catalogProductFulfilmentEstimates()->forceDelete();
            $product->catalogProductPrintPrices()->forceDelete();
            $product->catalogProductVariantSkus()->forceDelete();

            foreach ($views as $view) {
                $view->file()->forceDelete();
                $view->viewFile()->forceDelete();
            }
        });
    }

    //------------- - relations - --------------
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function fulfillmentEstimates(): HasMany
    {
        return $this->hasMany(ProductFulfillmentEstimate::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function printingMethods(): BelongsToMany
    {
        return $this->belongsToMany(PrintingMethod::class);
    }

    public function productPrintingPrices(): HasOne
    {
        return $this->hasOne(ProductPrintingPrice::class);
    }

    // Todo update this relation when table name will be updated
    public function linkedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'linked_catalog_product', 'catalog_product_id', 'linked_catalog_product_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
    //------------- - /relations - --------------


    public function generateSku(): string
    {
        $this->catalogProduct->load('productType');
        $productType = $this->catalogProduct->productType->name;
        $productType = str_replace(" ", "-", strtolower($productType));
        $productType = preg_replace("/[-]+/i", "-", $productType);
        $sku = 'SW-' . $this->id ;
        //. '-' . $productType
        return $sku;
    }

    /**
     * filter record
     * @param $query , $request
     */
    public function scopeSearch($query, $request)
    {
        // Searching
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('slug')) {
            $query->Where('slug', 'like', '%' . $request->slug . '%');
        }

        if ($request->has('catalog_product_type')) {
            $query->Where('catalog_product_type', 'like', '%' . $request->catalog_product_type . '%');
        }

        if ($request->has('is_mask')) {
            $query->Where('is_mask', $request->is_mask);
        }
        if ($request->has('sku')) {
            $query->Where('sku', $request->sku);
        }

        if ($request->has('is_active')) {
            if ($request->has('with_ids')) {
                $active = $request->is_active;
                $ids = explode(",", $request->with_ids);

                $query->where(function ($q) use ($active, $ids) {
                    $q->whereIn('id', $ids)
                        ->orwhere('is_active', '=', $active);
                });
            } else {
                $query->where('is_active', '=', $request->is_active);
            }
        }

        if ($request->has('only_ids')) {
            $ids = explode(",", $request->only_ids);
            $query->whereIn('id', $ids);
        }

        if ($request->has('brand')) {
            $brand = explode(",", $request->brand);
            $query->whereIn('brand_id', $brand);
        }

        if ($request->has('categories')) {
            $search = explode(",", $request->categories);
            $query->WhereHas('categories', function ($q) use ($search) {
                $q->whereIn('category_id', $search);
            });
        }

        if ($request->has('attribute_id')) {

            $search = explode(",", $request->attribute_id);
            $query->WhereHas('variants', function ($q) use ($search) {
                $q->whereIn('attribute_id', $search);
            });
        }
        if ($request->has('attribute_value_id')) {
            $search = explode(",", $request->attribute_value_id);
            $query->WhereHas('variants', function ($q) use ($search) {
                $q->whereIn('attribute_value_id', $search);
            });
        }


        return $query;
    }

    /**
     * return catalog product list for super admin
     * return active in-active list
     */
    public static function getAdminCatalog($request)
    {
        $query = static::query()->with(['productType', 'categories', 'brand', 'getPrices', 'linkProducts'])->withCount(['linkProducts', 'getPrices', 'mainVariants']);

        if ($request->all()) {
            $query = $query->search($request);
        }

        return self::getSorted($request, $query);
    }

    /**
     * return catalog product list for seller user
     * return active list
     */
    public static function getSallerCatalog($request)
    {
        $query = static::query()->where('is_active', 1)->with(['productType', 'categories', 'brand', 'getPrices', 'linkProducts'])->withCount(['linkProducts', 'getPrices', 'mainVariants']);


        if ($request->all()) {
            $query = $query->search($request);
        }

        // Sorting
        $catalogProducts = self::getSorted($request, $query);

        $variantAttribute = CatalogProductVariantValues::selectRaw('attribute_id,`catalog_product_id`, count(*) as `count`')->with('attributes')->groupBy('attribute_id')->get()->toArray();

        $totalAttribute = [];
        foreach ($variantAttribute as $attribute) {
            $totalAttribute[$attribute['catalog_product_id']][] = ['attribute_name' => $attribute['attributes']['name'], 'total' => $attribute['count']];
        }

        foreach ($catalogProducts as $key => $catalogProduct) {

            $catalogProducts[$key]['total_attribute'] = (isset($totalAttribute[$catalogProduct->id])) ? $totalAttribute[$catalogProduct->id] : null;
        }
        return $catalogProducts;
    }

    /**
     * @param $request
     * @param $query
     * @return mixed
     */
    private static function getSorted($request, $query)
    {
        $sortby = $request->sortby ?? 'created_at';
        $orderby = $request->orderby ?? 'desc';
        $query->orderBy($sortby, $orderby);
        $perPage = $request->per_page ?? config('constants.PER_PAGE');

        return $query->paginate($perPage);
    }

    //get key_features column value    
    public function getKeyFeaturesAttribute($value)
    {
        return (isset($value) && !empty($value)) ? unserialize($value) : [];
    }

    //set key_features column value  
    public function setKeyFeaturesAttribute($value)
    {
        settype($value, 'array');
        $this->attributes['key_features'] = serialize($value);
    }

    public function productVariantValue()
    {
        return $this->hasMany(CatalogProductVariantValues::class, 'catalog_product_id');
    }

    public function views()
    {
        return $this->hasMany(View::class)->orderBy('file_id', 'ASC');
    }

    public function getPrices()
    {
        return $this->hasMany(CatalogProductPrintingPrice::class, 'catalog_product_id')->where('is_active', '=', 1);
    }

    public function getMinPrice()
    {
        return $this->getPrices()->min('price') ? $this->getPrices()->min('price') : 0;
    }

    public function getMaxPrice()
    {
        return $this->getPrices()->max('price') ? $this->getPrices()->max('price') : 0;
    }


    public function simpleProductVariant()
    {
        return $this->mainVariants()->first();
    }

    public function availableAttributes()
    {
        return $this->hasMany(CatalogProductVariantBase::class, 'catalog_product_id');;
    }

    public function baseAttribute()
    {
        return $this->belongsTo(Attribute::class, 'base_attribute_id');
    }

    public function baseAttributesVariants()
    {
        $baseAttributeId = $this->base_attribute_id;

        return $this->mainVariants()->join('catalog_product_variant_values AS b', function ($join) {
            $join->on('b.catalog_product_id', '=', 'catalog_product_variants.catalog_product_id');
            $join->on('b.catalog_product_variant_id', '=', 'catalog_product_variants.id');
        })
            ->join('attribute_values AS c', 'c.id', '=', 'b.attribute_value_id')
            ->join('catalog_products AS d', 'd.id', '=', 'catalog_product_variants.catalog_product_id')
            ->groupBy('catalog_product_variants.id')
            ->selectRaw("catalog_product_variants.*,b.attribute_id,b.attribute_value_id,c.name, d.brand_id, c.hex_color_code,c.pattern,GROUP_CONCAT(IF(b.attribute_id = " . $baseAttributeId . ", CONCAT_WS(',',c.name,c.hex_color_code,c.pattern), NULL) SEPARATOR ':') as base_variant ,GROUP_CONCAT(IF(b.attribute_id != " . $baseAttributeId . ", c.name,NULL) ORDER BY b.id ASC SEPARATOR '/') as variant_attribute_name,GROUP_CONCAT(IF(b.attribute_id != 1, IFNULL(c.short_name, 'NULL'),NULL) ORDER BY b.id ASC SEPARATOR '/') as variant_attribute_short_name");
    }

    public function getVariantName()
    {
        $this->mainVariants->load(['productVariantValue']);
        $variantNames = [];
        foreach ($this->mainVariants as $key => $value) {
            $variationName = '';
            foreach ($value->productVariantValue as $K => $Value) {
                $variationName .= $Value->attributeValues->name . '-';

            }
            $variationName = rtrim($variationName, '-');
            $variantNames[$value->id] = $variationName;
        }
        return $variantNames;
    }

    public function getVariantNameFirstChar()
    {
        $this->mainVariants->load(['productVariantValue']);
        $variantNames = [];
        foreach ($this->mainVariants as $key => $value) {
            $variationName = '';
            foreach ($value->productVariantValue as $K => $Value) {
                $fName = substr($Value->attributeValues->name, 0, 1);
                $variationName .= $fName . '-';

            }
            $variationName = rtrim($variationName, '-');
            $variantNames[$value->id] = $variationName;
        }
        return $variantNames;
    }

    public function variantAttributesCombination()
    {
        $combination = [];
        $variantBase = [];
        $variantBaseVariants = [];
        $views = $this->views;

        foreach ($this->baseAttributesVariants as $key => $value) {
            $variants = [];
            $combination['attribute_base_name'] = $this->baseAttribute->name;
            $combination['attribute_base_id'] = $this->baseAttribute->id;

            $baseVariants = explode(',', $value->base_variant);
            $basePattern = $baseVariants[2] ?? null;
            $attributeVariants = explode('/', $value->variant_attribute_name);
            $attributeShortName = explode('/', $value->variant_attribute_short_name);
            $variationId = $value->id;
            $variationName = $baseVariants[0];
            $variantArr = [];
            foreach ($attributeVariants as $K => $Name) {
                $attributeName = (isset($attributeShortName[$K]) && $attributeShortName[$K] != 'NULL') ? $attributeShortName[$K] : $Name;
                $variationName .= '-' . $attributeName;
                $variantArr[] = $attributeName;
            }

            $variants = [
                'variation_id' => $variationId,
                'variation_name' => $variationName,
                'variant_attributes' => $variantArr,
            ];

            $viewData = [];
            foreach ($views as $k => $view) {
                $file = $view->viewFile->where('attribute_id', $value->attribute_id)->where('attribute_value_id', $value->attribute_value_id)->first();

                if ($file) {
                    $getFile = $file->file;
                    $imageFile = $this->getImageUrl($getFile->name);
                    $imageId = $getFile->id;
                } else {
                    $imageFile = null;
                    $imageId = null;
                }

                $viewData[$k] = [
                    'view_id' => $view->id,
                    'view_file_id' => $file->id,
                    'name' => $view->name,
                    'product_templates' => ProductTemplateResource::collection($view->productTemplates),
                    'file_url' => $imageFile,
                ];
            }

            $variantBaseVariants[$baseVariants[0]][] = $variants;

            if ($this->baseAttribute->id == $value->attribute_id) {
                $brandhexColor = $this->getBrandHexColor($value->brand_id, $value->attribute_value_id);

                $variantBase[$baseVariants[0]] = [
                    'attribute_value_id' => $value->attribute_value_id,
                    'attribute_name' => $baseVariants[0],
                    'attribute_hex_code' => $brandhexColor ? $brandhexColor->value : ($baseVariants[1] ?? null),
                    'attribute_pattern' => $basePattern ? config('constants.s3_bucket_url') . $basePattern : null,
                    'views' => $viewData,
                ];
            }
        }

        foreach ($variantBase as $AV => $variantAttribute) {

            $variantAttribute['variants'] = $variantBaseVariants[$AV];
            $combination['variant_base'][] = $variantAttribute;
        }

        return $combination;
    }


    private function getBrandHexColor($brand_id, $attribute_value_id)
    {

        $result = \DB::table('attribute_value_brands')->where([
            ["brand_id", "=", $brand_id],
            ["attribute_value_id", "=", $attribute_value_id]
        ])->first();

        return $result;
    }

    public function getVariantImages()
    {
        $variantBases = $this->getBaseVariantAttributes();
        $views = $this->views;

        foreach ($variantBases as $key => $Base) {

            $attributeId = $Base->attribute_id;
            $attributeValueId = $Base->attribute_value_id;
            $variantBases[$key]['attribute_value_id'] = $attributeValueId;
            $variantBases[$key]['name'] = $Base->attributeValues->name;
            $variantBases[$key]['short_name'] = $Base->attributeValues->short_name;
            $variantBases[$key]['hex_color_code'] = $Base->attributeValues->hex_color_code;
            $variantBases[$key]['pattern'] = $this->getImageUrl($Base->attributeValues->pattern);
            $variantBases[$key]['description'] = $Base->attributeValues->description;
            $variantBases[$key]['display_order'] = $Base->attributeValues->display_order;
            $variantBases[$key]['is_seeding'] = $Base->attributeValues->is_seeding;
            $viewData = [];
            foreach ($views as $k => $view) {
                $file = $view->viewFile->where('attribute_id', $attributeId)->where('attribute_value_id', $attributeValueId)->first();

                if ($file) {
                    $getFile = $file->file;
                    $imageFile = $this->getImageUrl($getFile->name);
                    $imageId = $getFile->id;
                } else {
                    $imageFile = null;
                    $imageId = null;
                }

                $viewData[$k] = [
                    'view_id' => $view->id,
                    'view_file_id' => $file->id,
                    'name' => $view->name,
                    'product_templates' => ProductTemplateResource::collection($view->productTemplates),
                    'file_url' => $imageFile,
                ];
            }
            unset($Base->attributeValues);
            $variantBases[$key]['views'] = $viewData;

            unset($variantBases[$key]['id']);
            unset($variantBases[$key]['created_by']);
            unset($variantBases[$key]['updated_by']);
            unset($variantBases[$key]['deleted_by']);
            unset($variantBases[$key]['created_at']);
            unset($variantBases[$key]['updated_at']);
            unset($variantBases[$key]['deleted_at']);
        }

        $this->variantBase->makeHidden('pivot');
        $this->variantBase->makeHidden('attributeValues');
        return $variantBases;
    }

    public function getImageUrl($fileName)
    {
        $url = (!empty($fileName)) ? config('constants.s3_bucket_url') . $fileName : null;
        return $url;
    }


    public function getVariantAttributes()
    {
        if ($this->catalog_product_type == 'simple') {
            return [];
        }

        $this->mainVariants->load(['productVariantValue', 'productVariantPrintPrices', 'productVariantSku', 'productVariants']);

        $variant = [];
        foreach ($this->mainVariants as $key => $value) {

            $catalogProductVariantValues = [];
            $combinationName = [];
            $productVariantsValues = $value->productVariantValue;


            foreach ($value->productVariantValue as $K => $Value) {

                $catalogProductVariantValues[] = [
                    'attribute_id' => $Value->attribute_id,
                    'attribute_value_id' => $Value->attribute_value_id,
                    'attribute_name' => $Value->myattributes->name,
                    'attribute_code' => $Value->myattributes->code,
                    'attribute_value_name' => $Value->attributeValues->name,
                    'attribute_value_short_name' => $Value->attributeValues->short_name,
                ];
                $combinationName[] = $Value->attributeValues->name;
            }

            $variant[] = [
                'attributes' => $catalogProductVariantValues,

                'details' => [

                    'catalog_product_variant_id' => $value->id,
                    'sku' => $value->productVariantSku->sku,
                    'length' => $value->length,
                    'width' => $value->width,
                    'height' => $value->height,
                    'weight' => $value->weight,
                    'variant_combination' => implode('-', $combinationName),
                    'prices' => $value->productVariantPrintPrices,
                ],
                'has_active_products' => count($value->productVariants)
                // 'has_active_products' => 0

            ];
            $value->productVariantPrintPrices->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by']);
        }

        return $variant;
    }

    public function catalogProductVariantSkus()
    {
        return $this->hasMany(CatalogProductVariantSku::class);
    }

    public function printingMethodWithEstimate()
    {
        $printMethods = [];
        $timeEstimate = $this->catalogProductFulfilmentEstimates()->groupBy('printing_method_slug')->get()->keyBy('printing_method_slug')->toArray();


        foreach ($this->catalogProductPrintingMethods as $key => $value) {
            $printMethods[] = [
                'name' => $value->printing_method_slug,
                'estimate' => $timeEstimate[$value->printing_method_slug]['time_estimate'] ?? null,
            ];
        }
        return $printMethods;
    }

    public function catalogProductFulfilmentEstimates()
    {
        return $this->hasMany(CatalogProductFulfilmentEstimate::class)->select('user_id', 'catalog_product_id', 'printing_method_slug', 'time_estimate');
    }

    public function catalogProductPrintPrices()
    {
        return $this->hasMany(CatalogProductPrintingPrice::class, 'catalog_product_id')->where('is_active', '=', 1);
    }
}
