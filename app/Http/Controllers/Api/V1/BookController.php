<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    /**
     * The Eloquent model of the resource.
     *
     * @var App\Models\Book
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @param App\Models\Book $model The model of the resource
     *
     * @return void
     */
    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    /**
     * Get a list of books.
     *
     * If a search query is provided, only books matching that search
     * query will be returned. Otherwise, all books are returned.
     *
     * @param string|null $title The search query
     *
     * @return App\Http\Resources\BookCollection The list of books
     */
    public function index(?string $title = null): BookCollection
    {
        $books = $this->model::with('stock')->paginate(20);
        if ($title) {
            $books = $this->model::where('title', 'like', '%' . $title . '%')->paginate(20);
        }
        return BookCollection::make($books);
    }

    /**
     * Get a single book.
     *
     * If the book is not found, a NotFoundHttpException is thrown.
     *
     * @param int $id The ID of the book
     *
     * @throws NotFoundHttpException If the book is not found
     *
     * @return App\Http\Resources\BookResource The book
     */
    public function show(int $id): BookResource
    {
        try {
            $book = $this->model->findOrFail($id)->load('stock');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('Book not found');
        }
        return BookResource::make($book);
    }
}
