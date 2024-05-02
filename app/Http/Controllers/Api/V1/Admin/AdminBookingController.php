<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBookingCollection;
use App\Http\Resources\Admin\AdminBookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AdminBookingCollection
     */
    public function index(Request $request): AdminBookingCollection
    {
        $status = $request->query('filter');

        $filters = ['active', 'returned', 'overdue'];

        $bookings = Booking::where(function ($query) use ($status, $filters) {
            if (in_array($status, $filters)) {
                $query->where('status', $status);
            }
        })->paginate(20);

        return AdminBookingCollection::make($bookings);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return AdminBookingResource
     */
    public function show(int $id): AdminBookingResource
    {
        $booking = Booking::find($id);

        if (!$booking) {
            throw new NotFoundHttpException('Booking not found');
        }

        return AdminBookingResource::make($booking);
    }

    /**
     * Returns a paginated list of bookings of a specified user.
     *
     * @param int $id The user ID
     *
     * @return AdminBookingCollection
     *
     * @throws NotFoundHttpException If no bookings were found for the user
     */
    public function showByUser(int $id): AdminBookingCollection
    {
        $bookings = Booking::where('user_id', $id)->paginate(20);

        if ($bookings->isEmpty()) {
            throw new NotFoundHttpException('No bookings to show');
        }

        return AdminBookingCollection::make($bookings);
    }

    /**
     * Mark the specified resource as returned.
     *
     * @param int $id
     * @return AdminBookingResource
     */
    public function return(int $id): AdminBookingResource
    {
        $booking = Booking::find($id)->load('book');

        if (!$booking) {
            throw new NotFoundHttpException('Booking not found');
        }

        $booking->update(['status' => 'returned']);
        $booking->book->increment('quantity');

        return AdminBookingResource::make($booking)->additional(['message' => 'Booking returned successfully']);
    }
}
