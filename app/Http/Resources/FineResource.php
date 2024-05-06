<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $booking = Booking::withTrashed()->whereUserId($this->user->id)->first();

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'booking' => BookingResource::make($booking),
        ];
    }
}
