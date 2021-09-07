<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleHourResource extends JsonResource
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
            'schedule_id' => $this->schedule_id,
            'day_number' => $this->day_number,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
