<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Models\User;
class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // if(is_null($this->sort)){
        //     return "-";
        // }
        return [
            'id'=> $this->id,
            'user_id' => UserResource::collection($this->id),
            'name' => $this->name,
            'age'=>$this->age,
            'sex'=>$this->sex,
            'type'=>$this->type,
            'notes'=>$this->notes,
            'image' => $this->image,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
           
        ];
     
    }
}
