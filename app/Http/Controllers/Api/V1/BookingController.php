<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Book;
use App\Models\Booking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingController extends Controller
{
    /**
     * The user model instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The booking model instance.
     *
     * @var \App\Models\Booking
     */
    protected $model;

    /**
     * The book model instance.
     *
     * @var \App\Models\Book
     */
    protected $book;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Booking $model The booking model instance
     * @param \App\Models\Book $book The book model instance
     * @return void
     */
    public function __construct(Booking $model, Book $book)
    {
        $this->user = auth()->user();
        $this->model = $model;
        $this->book = $book;
    }

    /**
     * Get the booking of the current user.
     *
     * @return \App\Http\Resources\BookingResource
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the user does not have any booking
     */
    public function index(): BookingResource
    {
        try {
            $booking = $this->model->where('user_id', $this->user->id)->firstOrFail()->load('book.stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any booking');
        }
        return BookingResource::make($booking);
    }

    /**
     * Create a new booking for the current user.
     *
     * @param int $id The book id
     * @return \App\Http\Resources\BookingResource
     * @throws \Exception If the user already has a booking
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the book is not found or not available
     */
    public function store(int $id): BookingResource
    {
        $userAlreadyHasBooking = $this->user->bookings;
        $defaultDueDate = 20;

        try {
            $book = $this->book->findOrFail($id)->load('stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }

        $stock = $book->stock;

        if ($userAlreadyHasBooking) {
            throw new \Exception('You already have a booking, please return it before borrowing a new one');
        } elseif (!$stock->available) {
            throw new NotFoundHttpException('Book not available for borrowing, please try again later');
        }

        Booking::create([
            'book_id' => $book->id,
            'user_id' => $this->user->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDay($defaultDueDate),
        ]);

        $booking = $this->model->where('user_id', $this->user->id)->firstOrFail()->load('book.stock');
        $stock = $booking->load('book.stock')->book->stock;
        $stock->decrement('quantity');

        if ($stock->quantity === 1) {
            $stock->update(['available' => false]);
        }

        return BookingResource::make($booking)
            ->additional(['message' => 'Booking created successfully']);
    }

    /**
     * Return a specific booking of the current user.
     *
     * @return \App\Http\Resources\BookingResource
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the user does not have any booking to return
     */
    public function return(): BookingResource
    {
        try {
            $booking = $this->model->where('user_id', $this->user->id)->firstOrFail()->load('book.stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any booking to return');
        }

        $stock = $booking->book->stock;

        $stock->increment('quantity');
        $stock->update(['available' => true]);
        $stock->save();

        $booking->update(['status' => 'returned', 'returned_at' => now()]);
        $booking->save();
        $booking->delete();

        return BookingResource::make($booking)
            ->additional(['message' => 'Booking returned successfully']);
    }
}
