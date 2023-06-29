<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Manufacturer Name' => $this->manufacturer_name,
            'Address' => $this->address,
            'Mobile Number' => $this->mobile_number,
            'Email' => $this->email  
        ];
    }
}
