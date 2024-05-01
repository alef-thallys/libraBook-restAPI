<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Models\Book;
use App\Models\Booking;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->user()->id)->paginate(10)->load(['user', 'book']);
        return BookingCollection::make($bookings);
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) throw new NotFoundHttpException('Booking not found');

        Gate::authorize('can-handle', $booking->user_id);

        return BookingResource::make($booking);
    }

    public function store($id)
    {
        $book = Book::find($id);
        $user = auth()->user();

        $bookingAvailable = $book || $book->quantity > 0;

        if (!$bookingAvailable) {
            throw new NotFoundHttpException('Book not available for borrowing');
        } elseif ($user->bookings->contains('book_id', $book->id)) {
            throw new \Exception('Book already borrowed');
        }

        $book->decrement('quantity');

        $booking = Booking::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
        ]);

        $booking->load(['user', 'book']);

        return response()->json([
            'data' => [
                'user' => $booking->user->name,
                'book' => $booking->book->title,
                'status' => $booking->status
            ],
            'message' => 'Booking created successfully, please wait for confirmation'
        ]);
    }

    public function cancel($id)
    {
        $booking = Booking::find($id);

        if (!$booking) throw new NotFoundHttpException('Booking not found');

        Gate::authorize('can-handle', $booking->user_id);

        $booking->delete();
        $booking->book->increment('quantity');

        return response()->json([
            'message' => 'Booking canceled successfully'
        ]);
    }
}
