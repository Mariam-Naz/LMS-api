<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagePrerequisiteResource extends JsonResource
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
            'package_id' => $this->package_id,
            'pre_package_id' => $this->pre_package_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
