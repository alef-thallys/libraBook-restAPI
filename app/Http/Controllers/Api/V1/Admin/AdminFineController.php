<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminFineCollection;
use App\Http\Resources\Admin\AdminFineResource;
use App\Models\Fine;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminFineController extends Controller
{
    protected $model;

    public function __construct(Fine $model)
    {
        $this->model = $model;
    }

    public function index(): AdminFineCollection
    {
        $fines = $this->model->paginate(20);
        if ($fines->isEmpty()) {
            throw new NotFoundHttpException('No fines to show');
        }
        return AdminFineCollection::make($fines);
    }

    public function show(int $id): AdminFineResource
    {
        try {
            $fine = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Fine not found');
        }
        return AdminFineResource::make($fine);
    }
}
