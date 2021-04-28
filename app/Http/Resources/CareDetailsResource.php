<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CareDetailsResource extends JsonResource
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
            "id" => $this['id'],
            "description" => $this['description'],
        ];
    }
}
