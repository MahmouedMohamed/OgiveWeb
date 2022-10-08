<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemoryPaginationResource extends JsonResource
{

    public function __construct(private $data)
    {
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = collect($this->data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'person_name' => $item->person_name,
                'birth_date' => $item->birth_date,
                'death_date' => $item->death_date,
                'age' => $item->age,
                'brief' => $item->brief,
                'life_story' => $item->life_story,
                'image' => url()->current() . $item->image,
                'nationality' => $item->nationality,
                'created_by' => UserResource::make($item->author),
                'updated_at' => $item->updated_at,
                'created_at' => $item->created_at,
                'number_of_likes' => $item->numberOfLikes,
            ];
        });
        return [
            'data' => $data,
            'total' => $this->data->total(),
            'path' => $this->data->path(),
            'per_page' => $this->data->perPage(),
            'next_page_url' => $this->data->nextPageUrl(),
            'prev_page_url' =>  $this->data->previousPageUrl(),
        ];
    }
}
