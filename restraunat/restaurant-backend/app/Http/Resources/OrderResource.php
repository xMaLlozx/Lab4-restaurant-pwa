<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderResource extends JsonResource {
    public function toArray($request) {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'total'          => $this->total,
            'address'        => $this->address,
            'payment_method' => $this->payment_method,
            'comment'        => $this->comment,
            'items'          => CartItemResource::collection($this->whenLoaded('items')),
            'created_at'     => $this->created_at,
        ];
    }
}
