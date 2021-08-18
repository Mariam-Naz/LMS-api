<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'cart_id' => $this->cart_id,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'transaction_type' => $this->transaction_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
