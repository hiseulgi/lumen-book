<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Transformer\BookTransformer;
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
        return $this->collection(Book::all(), new BookTransformer());
    }

    /**
     * GET /books/{id}
     * @param integer $id
     * @return mixed
     */
    // Test with: curl http://localhost/books/1
    public function show($id)
    {
        return $this->item(Book::findOrFail($id), new BookTransformer());
    }

    /**
     * POST /books
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // Test with: curl -X POST http://localhost/books -d "title=foo&description=bar&author=baz"
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]);
        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer());

        return response()->json($data, 201, [
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

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]);

        $book->fill($request->all());
        $book->save();

        return $this->item($book, new BookTransformer());
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
