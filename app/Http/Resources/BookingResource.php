<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'borrowed_at' => $this->borrowed_at,
            'due_date' => $this->due_date,
            'returned_at' => $this->returned_at,

            'book' => BookResource::make($this->book)
        ];
    }
}
