<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    /**
     * Get a list of books
     *
     * @return BookCollection
     */
    public function index(): BookCollection
    {
        $books = Book::paginate(20);

        if ($books->isEmpty()) {
            throw new NotFoundHttpException('No books to show');
        }

        return BookCollection::make($books);
    }

    /**
     * Get a single book
     *
     * @param int $id ID of the book
     *
     * @throws NotFoundHttpException If the book is not found
     *
     * @return BookResource
     */
    public function show(int $id): BookResource
    {
        $book = Book::find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        return BookResource::make($book);
    }
}
