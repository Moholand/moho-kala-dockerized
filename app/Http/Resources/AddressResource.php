<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'street'      => $this->street,
            'postal_code' => $this->postal_code,
            'phone'       => $this->phone,
            'label'       => $this->label,
            'is_default'  => $this->is_default,
            'location'    => $this->whenLoaded('location', fn () => new LocationResource($this->location)),
        ];
    }
}
