<?php

namespace App\Http\Resources;

use App\Services\Category\CategoryService;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'title' => $this['name'],
            'slug' => $this['slug'],
            'image' => array_key_exists($this['id'], $this['image_urls'])
                ? $this['image_urls'][$this['id']]
                : null,
//          'products' => this set in CategoryService,
        ];
    }
}
