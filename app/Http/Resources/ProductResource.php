<?php

namespace App\Http\Resources;

use App\Services\Category\CategoryService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  $request
     * @return array
     */
    public function toArray($request): array
    {
        $attributes = [];
        $productPrices = [];
        if (count($this['variants'])) {
            foreach ($this['variants'] as $variant) {
                if (isset($variant['attribute_values']) && count($variant['attribute_values'])) {
                    $attr = $variant['attribute_values'][0]['attribute'];
                    foreach ($variant['attribute_values'] as $jey => $attributeValue) {

                        if ($attributeValue['attribute'] !== $attr){
                            $attr = $attributeValue['attribute'];
                        }

                        $attributes[$attr['id']]['id'] = $attr['id'];
                        $attributes[$attr['id']]['name'] = $attr['name'];

                        $attributes[$attr['id']]['values'][$jey]['id'] = $attributeValue['id'];
                        $attributes[$attr['id']]['values'][$jey]['code'] = $attributeValue['hexcode'] && $attributeValue['hexcode']['code_6'] ? $attributeValue['hexcode']['code_6'] : 'no hexcode';
                        $attributes[$attr['id']]['values'][$jey]['name'] = $attributeValue['name'];
                        $attributes[$attr['id']]['values'][$jey]['default'] = 'dummyfalse';

                    }
                }
                if (isset($variant['product_variant_prices']) && count($variant['product_variant_prices'])) {
                    $prices = collect($variant['product_variant_prices'])->pluck('price_in_cents')->toArray();
                    $productPrices['min_price'] = min($prices);
                    $productPrices['max_price'] = max($prices);
                }
                $this['size_chart'] = ' ';
                $this['canvas_width_in_px'] = ' ';
            }
            unset($this['variants']);
        }

        return [
            'catalog_product_id' => $this['id'],
            'name' => $this['name'],
            'sku' => $this['sku'],
            'about' => $this['description'],
            'product_type_id' => $this['product_type_id'],
            'care_instructions' => new CareDetailsResource($this['care_detail']),
            'printing_methods' => PrintingMethodResource::collection($this['printing_methods']),
            'attributes' => $attributes,
            'size_chart' => [
                "imperial" => [
                    "XS" => [
                        "width" => "17.49",
                        "length" => "27.01",
                        "sleeve" => "7.49"
                    ],
                    "S" => [
                        "width" => "19.01",
                        "length" => "29.01",
                        "sleeve" => "8.99"
                    ],
                    "M" => [
                        "width" => "20.07",
                        "length" => "31.03",
                        "sleeve" => "9.99"
                    ],
                    "L" => [
                        "width" => "21.04",
                        "length" => "33.02",
                        "sleeve" => "11.02"
                    ],
                    "XL" => [
                        "width" => "21.04",
                        "length" => "33.02",
                        "sleeve" => "11.02"
                    ],
                    "2XL" => [
                        "width" => "22.06",
                        "length" => "35.03",
                        "sleeve" => "13.02"
                    ],
                    "3XL" => [
                        "width" => "23.03",
                        "length" => "37.03",
                        "sleeve" => "14.88"
                    ]
                ],
                "metric" => ["XS" =>
                    [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "S" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "M" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "L" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "XL" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "2XL" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ],
                    "3XL" => [
                        "width" => "44.42",
                        "length" => "68.60",
                        "sleeve" => "19.02"
                    ]
                ]
            ],
            'brand' => new  BrandRecource($this['brand']),
            'price' => $productPrices,
            'canvas_width_in_px' => "1121.0000000000000000",
            'available_attributes' => $this->setAvailableAttributes($this),
            'views' => $this->setViews($this),

        ];
    }

    private function setAvailableAttributes($product): array
    {
        return [
            'base_id' => $product['base_attribute']['id'],
            'base_name' => $product['base_attribute']['name'],
            'variant_base' => array_key_exists($product['id'], $product['variant_bases'])
            && array_key_exists('variant_base', $product['variant_bases'][$product['id']])
                ? $product['variant_bases'][$product['id']]['variant_base'] : []
        ];
    }

    private function setViews($product): array
    {
        return array_key_exists($product['id'], $product['view_data'])
            ? $product['view_data'][$product['id']] : [];
    }
}
