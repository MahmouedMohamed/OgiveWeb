<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemoryResource extends JsonResource
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
            'person_name' => $this->person_name,
            'birth_date' => $this->birth_date,
            'death_date' => $this->death_date,
            'age' => $this->age,
            'brief' => $this->brief,
            'life_story' => $this->life_story,
            'image' => url()->current() . $this->image,
            'nationality' => $this->nationality,
            'created_by' => UserResource::make($this->author),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'number_of_likes' => $this->numberOfLikes,
        ];
    }
}
