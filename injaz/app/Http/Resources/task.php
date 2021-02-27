<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'status' => $this->status,
            'the_date' => $this->the_date,
            'the_day' => $this->the_day,
            'created_at' => $this->created_at->format('d-m-Y h:m:s'),
            'updated_at' => $this->updated_at->format('d-m-Y h:m:s'),
        ];
    }
}
