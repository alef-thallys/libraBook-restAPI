<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FineCollection;
use App\Http\Resources\FineResource;
use App\Models\Fine;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FineController extends Controller
{
    /**
     * Display a list of fines for the authenticated user
     *
     * @return FineCollection
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $fines = Fine::where('user_id', $user_id)->paginate(20);

        if ($fines->isEmpty()) {
            throw new NotFoundHttpException('No fines to show');
        }

        $fines->load(['book']);

        return FineCollection::make($fines);
    }

    /**
     * Display a fine
     *
     * @param int $id
     * @return FineResource
     * @throws NotFoundHttpException
     */
    public function show(int $id)
    {
        $fine = Fine::find($id);

        if (!$fine) {
            throw new NotFoundHttpException('Fine not found');
        }

        Gate::authorize('is-owner', $fine->user_id);

        return FineResource::make($fine);
    }

    /**
     * Pay all fines for the authenticated user
     *
     * @return JsonResponse
     */
    public function payAll()
    {
        $user_id = auth()->user()->id;
        $fines = Fine::where('user_id', $user_id)->get();

        if ($fines->isEmpty()) {
            throw new NotFoundHttpException('No fines to show');
        }

        foreach ($fines as $fine) {
            $fine->update(['paid' => true]);
            $fine->delete();
        }

        return response()->json([
            'message' => 'All fines paid successfully'
        ]);
    }

    /**
     * Pay a fine
     *
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function pay(int $id)
    {
        $fine = Fine::find($id);

        if (!$fine) {
            throw new NotFoundHttpException('Fine not found');
        }

        Gate::authorize('is-owner', $fine->user_id);

        $fine->update(['paid' => true]);
        $fine->delete();

        return response()->json([
            'message' => 'Fine paid successfully'
        ]);
    }
}
