<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminStockCollection;
use App\Http\Resources\Admin\AdminStockResource;
use App\Models\Stock;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminStockController extends Controller
{
    /**
     * Returns a paginated list of all stock items
     *
     * @return AdminStockCollection
     * @throws NotFoundHttpException
     */
    public function index()
    {
        $stock = Stock::paginate(20);

        if ($stock->isEmpty()) {
            throw new NotFoundHttpException('No stock to show');
        }

        return AdminStockCollection::make($stock);
    }

    /**
     * Returns a single stock item by its ID
     *
     * @param int $id The stock item ID
     * @return AdminStockResource
     * @throws NotFoundHttpException
     */
    public function show(int $id)
    {
        $stock = Stock::find($id);

        if (!$stock) {
            throw new NotFoundHttpException('Stock not found');
        }

        return AdminStockResource::make($stock);
    }

    /**
     * Updates a stock item by its ID
     *
     * @param int $id The stock item ID
     * @param Request $request The request with the updated data
     * @return AdminStockResource
     * @throws NotFoundHttpException
     */
    public function update(int $id, Request $request)
    {
        $stock = Stock::find($id);

        if (!$stock) {
            throw new NotFoundHttpException('Stock not found');
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($data['quantity'] == 0) {
            $data['available'] = false;
        } else {
            $data['available'] = true;
        }

        $stock->update($data);

        return AdminStockResource::make($stock)->additional(['message' => 'Stock updated successfully']);
    }
}
