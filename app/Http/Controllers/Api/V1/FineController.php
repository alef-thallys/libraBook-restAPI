<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FineResource;
use App\Models\Booking;
use App\Models\Fine;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FineController extends Controller
{
    protected $user;
    protected $model;

    public function __construct(Fine $fine)
    {
        $this->user = auth()->user();
        $this->model = $fine;
    }

    public function show()
    {
        try {
            $fines = $this->model->whereUserId($this->user->id)->firstOrFail()->load('book.bookings');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any fine');
        }

        return FineResource::make($fines);
    }

    public function pay(): JsonResponse
    {
        try {
            $fine = $this->model->whereUserId($this->user->id)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any fine');
        }

        $bookingWasReturned = Booking::withTrashed()->whereUserId($this->user->id)->where('status', 'active')->exists();

        if ($bookingWasReturned) {
            throw new NotFoundHttpException('You have not returned the book yet, please return it first');
        }

        $fine->delete();
        Booking::whereUserId($this->user->id)->forceDelete();

        return response()->json([
            'message' => 'Fine paid successfully!'
        ], 200);
    }
}
