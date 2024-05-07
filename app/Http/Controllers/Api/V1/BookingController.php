<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Book;
use App\Models\Booking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingController extends Controller
{
    protected $user;
    protected $model;
    protected $book;

    public function __construct(Booking $model, Book $book)
    {
        $this->user = auth()->user();
        $this->model = $model;
        $this->book = $book;
    }

    public function show(): BookingResource
    {
        try {
            $booking = $this->model->whereUserId($this->user->id)->firstOrFail()->load('book.stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any booking');
        }
        return BookingResource::make($booking);
    }

    public function store(int $id): JsonResponse
    {
        $userAlreadyHasBooking = $this->user->bookings;
        $userHasFines = $this->user->fines;
        $defaultDueDate = 20;

        try {
            $book = $this->book->findOrFail($id)->load('stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }

        $stock = $book->stock;

        if ($userAlreadyHasBooking) {
            throw new \Exception('You already have a booking, please return it before borrowing a new one');
        } elseif ($userHasFines) {
            throw new \Exception('You have a fine, please pay it before borrowing a new one');
        } elseif (!$stock->available) {
            throw new NotFoundHttpException('Book not available for borrowing, please try again later');
        }

        $booking = Booking::create([
            'book_id' => $book->id,
            'user_id' => $this->user->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDay($defaultDueDate),
        ])->load('book.stock');

        $booking->refresh();
        $stock->decrement('quantity');

        if ($stock->quantity < 2) {
            $stock->update(['available' => false]);
        }

        return response()->json([
            'message' => 'Book reserved successfully!',
        ], 201);
    }

    public function return(): JsonResponse
    {
        $userDoesNotHaveFines = $this->user->fines;

        try {
            $booking = $this->model->whereUserId($this->user->id)->firstOrFail()->load('book.stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any booking to return');
        }

        $stock = $booking->book->stock;
        $stock->increment('quantity');
        $stock->update(['available' => true]);
        $booking->update(['status' => 'returned', 'returned_at' => now()]);
        $booking->delete();

        if (!$userDoesNotHaveFines) $booking->forceDelete();

        return response()->json([
            'message' => 'Book returned successfully!'
        ], 200);
    }
}
