<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookStoreRequest;
use App\Http\Requests\Admin\BookUpdateRequest;
use App\Http\Resources\Admin\AdminBookCollection;
use App\Http\Resources\Admin\AdminBookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminBookController extends Controller
{
    /**
     * Get all books.
     *
     * @return AdminBookCollection
     * @throws NotFoundHttpException
     */
    public function index(?string $title = null): AdminBookCollection
    {
        $books = Book::paginate(20);

        if ($books->isEmpty()) {
            throw new NotFoundHttpException('No books to show');
        }

        if ($title) {
            $books = Book::where('title', 'like', '%' . $title . '%')->paginate(20);
        }

        return AdminBookCollection::make($books);
    }

    /**
     * Get a single book.
     *
     * @param int $id
     * @return AdminBookResource
     * @throws NotFoundHttpException
     */
    public function show(int $id): AdminBookResource
    {
        $book = Book::find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        return AdminBookResource::make($book);
    }

    /**
     * Store a new book.
     *
     * @param BookStoreRequest $request
     * @return AdminBookResource
     */
    public function store(BookStoreRequest $request): AdminBookResource
    {
        $data = $request->validated();

        $book = Book::create($data);

        $book->stock()->create();

        return AdminBookResource::make($book)
            ->additional(['message' => 'Book created successfully']);
    }

    /**
     * Update a book.
     *
     * @param BookUpdateRequest $request
     * @param int $id
     * @return AdminBookResource
     * @throws NotFoundHttpException
     */
    public function update(BookUpdateRequest $request, int $id): AdminBookResource
    {
        $book = Book::find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255', 'unique:books,title,' . $id],
        ]);

        $book->update($data);

        return AdminBookResource::make($book)
            ->additional(['message' => 'Book updated successfully']);
    }

    /**
     * Delete a book.
     *
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function destroy(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        if ($book->bookings->count() > 0) {
            throw new NotFoundHttpException('Book has active bookings');
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
