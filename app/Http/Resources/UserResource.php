<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        $obj = self::toObject($this);
        return ($obj);
    }

    public static function toObject($obj)
    {
        return [
            "id" => $obj->id,
            "role_id" => $obj->role_id,
            "name" => $obj->name,
            "email" => $obj->email,
            "avatar" => $obj->profile_image,
        ];
    }
}
