<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookStoreRequest;
use App\Http\Requests\Admin\BookUpdateRequest;
use App\Http\Resources\Admin\AdminBookCollection;
use App\Http\Resources\Admin\AdminBookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminBookController extends Controller
{
    protected $model = Book::class;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function index(?string $title = null): AdminBookCollection
    {
        $books = $this->model::with('stock')->paginate(20);
        if ($books->isEmpty()) {
            throw new NotFoundHttpException('No books to show');
        } elseif ($title) {
            $books = $this->model::where('title', 'like', '%' . $title . '%')->paginate(20);
        }
        return AdminBookCollection::make($books);
    }

    public function show(int $id): AdminBookResource
    {
        try {
            $book = $this->model->findOrFail($id)->load('stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }
        return AdminBookResource::make($book);
    }

    public function store(BookStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $book = $this->model::create($data);
        $book->stock()->create();

        return response()->json([
            'message' => 'Book created successfully!'
        ], 201);
    }

    public function update(BookUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $book = $this->model::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }

        $data = $request->validated();
        $book->update($data);
        return response()->json([
            'message' => 'Book updated successfully!'
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $book = $this->model::findOrFail($id)->load('bookings');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }

        if ($book->bookings->isNotEmpty()) {
            throw new NotFoundHttpException('Book has active bookings');
        }

        $book->delete();
        return response()->json([
            'message' => 'Book deleted successfully!'
        ], 200);
    }
}
