<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    public function index(): BookCollection
    {
        $books = Book::paginate(10);
        return BookCollection::make($books);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) throw new NotFoundHttpException('Book not found');

        return BookResource::make(Book::find($id));
    }
}
