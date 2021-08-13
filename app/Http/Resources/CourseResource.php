<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hours' => $this->hours,
            'price_per_hour' => $this->price_per_hour,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
