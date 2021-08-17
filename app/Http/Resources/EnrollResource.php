<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollResource extends JsonResource
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
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'courses' => $this->courses,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'date_range' => $this->date_range,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
