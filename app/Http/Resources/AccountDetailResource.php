<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountDetailResource extends JsonResource
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
            'account_type' => $this->account_type,
            'payment_method' => $this->payment_method,
            'account_number' => $this->account_number,
            'reference_name' => $this->reference_name,
            'reference_email' => $this->reference_email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
