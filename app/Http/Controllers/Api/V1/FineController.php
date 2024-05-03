<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FineResource;
use App\Models\Fine;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FineController extends Controller
{
    /**
     * The authenticated user
     *
     * @var User
     */
    protected $user;

    /**
     * The Fine model
     *
     * @var Fine
     */
    protected $model;

    /**
     * Constructor
     *
     * @param Fine $fine The Fine model
     */
    public function __construct(Fine $fine)
    {
        $this->user = auth()->user();
        $this->model = $fine;
    }

    /**
     * Display a list of fines for the authenticated user
     *
     * @return FineResource fine
     */
    public function index(): FineResource
    {
        try {
            $fines = $this->model->where('user_id', $this->user->id)->firstOrFail()->load('book');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any fine');
        }
        return FineResource::make($fines);
    }

    /**
     * Pay the fine for the authenticated user
     *
     * @return JsonResponse The success message
     */
    public function pay(): JsonResponse
    {
        try {
            $fine = $this->model->where('user_id', $this->user->id)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Do not have any fine');
        }
        $fine->delete();
        return response()->json(['message' => 'Fine paid successfully']);
    }
}
