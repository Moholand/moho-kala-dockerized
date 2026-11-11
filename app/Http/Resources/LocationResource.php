<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'type'      => $this->type,
            'parent_id' => $this->parent_id,
            'parent'    => $this->whenLoaded('parent', fn () => new LocationResource($this->parent)),
        ];
    }
}
