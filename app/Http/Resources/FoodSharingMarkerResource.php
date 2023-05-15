<?php

namespace App\Http\Resources;

use App\ConverterModels\OwnerType;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodSharingMarkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'owner_type' => $this->owner_type,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'type' => $this->type,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'priority' => $this->priority,
            'collected' => $this->collected,
            'collected_at' => $this->collected_at,
            'nationality' => $this->nationality,
            'created_at' => $this->created_at,
            'user' => $this->when(OwnerType::$value[class_basename(request()->user)] == 1, UserResource::make($this->user)),
        ];
    }
}
