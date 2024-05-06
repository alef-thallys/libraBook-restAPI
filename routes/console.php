<?php

use App\Models\Booking;
use App\Models\Fine;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $bookings = Booking::with('fine')
        ->where('due_date', '<', now())
        ->whereStatus('active')
        ->get();

    foreach ($bookings as $booking) {
        Fine::create([
            'user_id' => $booking->user_id,
            'book_id' => $booking->id,
            'amount' => 10,
        ]);
        $booking->update(['status' => 'overdue']);
    }
})->everySecond();
