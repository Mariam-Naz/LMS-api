<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionResource extends JsonResource
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
            'initiator_id' => $this->initiator_id,
            'schedule_id' => $this->schedule_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
