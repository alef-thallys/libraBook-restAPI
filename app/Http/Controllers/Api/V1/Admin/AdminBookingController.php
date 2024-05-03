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
    public function index(?int $user_id = null): AdminBookingCollection
    {
        $bookings = Booking::paginate(20);

        if ($bookings->isEmpty()) {
            throw new NotFoundHttpException('No bookings to show');
        }

        if ($user_id) {
            $bookings = Booking::where('user_id', $user_id)->paginate(20);
        }

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
}
