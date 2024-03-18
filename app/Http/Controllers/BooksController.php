<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BooksController extends Controller
{
    /**
     * GET /books
     * @return array
     */

    public function index()
    {
        return Book::all();
    }
}
