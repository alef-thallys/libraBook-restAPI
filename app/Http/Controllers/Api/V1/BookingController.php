<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Models\Book;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingController extends Controller
{
    /**
     * Return all bookings of the authenticated user.
     *
     * @return BookingCollection
     */
    public function index(): BookingCollection
    {
        $userId = auth()->user()->id;
        $bookings = Booking::where('user_id', $userId)->paginate(20);

        if ($bookings->isEmpty()) {
            throw new NotFoundHttpException('No bookings to show');
        }

        $bookings->load(['book']);

        return BookingCollection::make($bookings);
    }

    /**
     * Return a booking by its ID.
     *
     * @param int $id
     * @return BookingResource
     * @throws NotFoundHttpException
     */
    public function show(int $id): BookingResource
    {
        $booking = Booking::find($id);

        if (!$booking) {
            throw new NotFoundHttpException('Booking not found');
        }

        Gate::authorize('is-owner', $booking->user_id);

        return BookingResource::make($booking);
    }

    /**
     * Create a new booking for the authenticated user.
     *
     * @param int $id
     * @return BookingResource
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function store(int $id): BookingResource
    {
        $book = Book::find($id);
        $user = auth()->user();
        $bookingAvailable = $book || $book->quantity > 0;

        if (!$bookingAvailable) {
            throw new NotFoundHttpException('Book not available for borrowing');
        } elseif ($user->bookings->contains('book_id', $book->id)) {
            throw new \Exception('Book already borrowed');
        }

        Booking::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
        ]);

        $booking = Booking::where('book_id', $book->id)->where('user_id', $user->id)->first();
        $book->decrement('quantity');
        $booking->load(['user', 'book']);

        return BookingResource::make($booking)
            ->additional(['message' => 'Booking created successfully, please wait for confirmation']);
    }

    /**
     * Cancel a booking of the authenticated user.
     *
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function cancel(int $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (!$booking) {
            throw new NotFoundHttpException('Booking not found');
        }

        Gate::authorize('is-owner', $booking->user_id);

        $booking->delete();
        $booking->book->increment('quantity');

        return response()->json([
            'message' => 'Booking canceled successfully'
        ]);
    }
}
