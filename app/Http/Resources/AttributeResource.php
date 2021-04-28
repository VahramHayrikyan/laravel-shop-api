<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'name' => $this['name'],
            'values' => AttributeValueRecource::collection($this['values']),
            // Todo check this part - the default field
            'default' => false,
        ];
    }
}
