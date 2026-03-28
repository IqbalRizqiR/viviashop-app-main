<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $this->status,
            'order_date' => $this->order_date,
            'payment_due' => $this->payment_due,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'payment_token' => $this->when($this->payment_token, $this->payment_token),
            'payment_url' => $this->when($this->payment_url, $this->payment_url),
            'base_total_price' => (float) $this->base_total_price,
            'tax_amount' => (float) $this->tax_amount,
            'discount_amount' => (float) $this->discount_amount,
            'shipping_cost' => (float) $this->shipping_cost,
            'grand_total' => (float) $this->grand_total,
            'shipping_service_name' => $this->shipping_service_name,
            'shipping_courier' => $this->shipping_courier,
            'note' => $this->note,
            'notes' => $this->notes,
            'customer' => [
                'first_name' => $this->customer_first_name,
                'last_name' => $this->customer_last_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
                'address1' => $this->customer_address1,
                'address2' => $this->customer_address2,
                'postcode' => $this->customer_postcode,
            ],
            'items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'shipment' => $this->whenLoaded('shipment', function () {
                return [
                    'id' => $this->shipment->id,
                    'track_number' => $this->shipment->track_number,
                    'status' => $this->shipment->status,
                    'shipped_by' => $this->shipment->shipped_by,
                    'shipped_at' => $this->shipment->shipped_at,
                ];
            }),
            'handled_by' => $this->handled_by,
            'use_employee_tracking' => (bool) $this->use_employee_tracking,
            'approved_at' => $this->approved_at,
            'cancelled_at' => $this->cancelled_at,
            'cancellation_note' => $this->cancellation_note,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
