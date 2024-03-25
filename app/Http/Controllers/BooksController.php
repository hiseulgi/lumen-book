<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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

    /**
     * GET /books/{id}
     * @param integer $id
     * @return mixed
     */
    // Test with: curl http://localhost/books/1
    public function show($id)
    {
        try {
            return Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
    }

    /**
     * POST /books
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // Test with: curl -X POST http://localhost/books -d "title=foo&description=bar&author=baz"
    public function store(Request $request)
    {
        $book = Book::create($request->all());

        return response()->json(['created' => true], 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
    }

    /**
     * PUT /books/{id}
     * @param Request $request
     * @param integer $id
     * @return mixed
     */
    // Test with: curl -X PUT http://localhost/books/1 -d "title=foo&description=bar&author=baz"
    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                    ]
                ], 404);
            }

        $book->fill($request->all());
        $book->save();

        return $book;
    }

    /**
     * DELETE /books/{id}
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    // Test with: curl -X DELETE http://localhost/books/1
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }

        $book->delete();

        return response(null, 204);
    }
}
