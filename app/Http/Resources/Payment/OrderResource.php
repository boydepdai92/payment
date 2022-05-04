<?php

namespace App\Http\Resources\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Order $resource */
        $resource = $this->resource;

        return [
            'charge_id' => $resource->id,
            'amount'    => $resource->amount,
            'currency'  => $resource->currency,
            'processor' => $resource->acquirer,
            'status'    => $resource->status
        ];
    }
}
