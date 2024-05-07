<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminStockCollection;
use App\Http\Resources\Admin\AdminStockResource;
use App\Models\Stock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminStockController extends Controller
{
    protected $model;

    public function __construct(Stock $model)
    {
        $this->model = $model;
    }

    public function index(): AdminStockCollection
    {
        $stock = $this->model::paginate(20);
        if ($stock->isEmpty()) {
            throw new NotFoundHttpException('No stock to show');
        }
        return AdminStockCollection::make($stock);
    }

    public function show(int $id): AdminStockResource
    {
        try {
            $stock = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Stock not found');
        }
        return AdminStockResource::make($stock);
    }

    public function update(int $id, int $quantity): JsonResponse
    {
        try {
            $stock = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Stock not found');
        }

        $available = false;
        if ($quantity > 0) $available = true;

        $data = [
            'available' => $available,
            'quantity' => $quantity
        ];

        $stock->update($data);
        return response()->json([
            'message' => 'Stock quantity updated successfully!'
        ]);
    }
}
