<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikePaginationResource extends JsonResource
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
                'type' => $item->type,
                'memory' => MemoryResource::make($item->memory)
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
