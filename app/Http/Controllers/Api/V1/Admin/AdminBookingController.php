<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminBookingCollection;
use App\Http\Resources\Admin\AdminBookingResource;
use App\Models\Booking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminBookingController extends Controller
{
    protected $model;

    public function __construct(Booking $model)
    {
        $this->model = $model;
    }

    public function index(): AdminBookingCollection
    {
        $bookings = $this->model->paginate(20);
        if ($bookings->isEmpty()) {
            throw new NotFoundHttpException('No bookings to show');
        }

        return new AdminBookingCollection($bookings);
    }

    public function show(int $id): AdminBookingResource
    {
        try {
            $booking = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Booking not found');
        }
        return new AdminBookingResource($booking);
    }
}
