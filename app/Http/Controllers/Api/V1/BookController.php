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
    protected $model;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function index(?string $title = null): BookCollection
    {
        $books = $this->model::with('stock')->paginate(20);
        if ($title) {
            $books = $this->model::with('stock')->where('title', 'like', '%' . $title . '%')->paginate(20);
        }
        return BookCollection::make($books);
    }

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
