<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminFineCollection;
use App\Http\Resources\Admin\AdminFineResource;
use App\Models\Fine;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminFineController extends Controller
{
    public function index(?int $user_id = null,): AdminFineCollection
    {
        $fines = Fine::paginate(20);

        if ($fines->isEmpty()) {
            throw new NotFoundHttpException('No fines to show');
        }

        if ($user_id) {
            $fines = Fine::where('user_id', $user_id)->paginate(20);
        }

        return AdminFineCollection::make($fines);
    }

    public function show(int $id): AdminFineResource
    {
        $fine = Fine::find($id);

        if (!$fine) {
            throw new NotFoundHttpException('Fine not found');
        }

        return AdminFineResource::make($fine);
    }
}
