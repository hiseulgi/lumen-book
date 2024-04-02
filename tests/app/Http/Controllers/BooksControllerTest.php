<?php

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use App\Models\Book;
use Laravel\Lumen\Testing\DatabaseMigrations;

class BooksControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function index_status_code_should_be_200()
    {
        $this->get('/books')->seeStatusCode(200);
    }

    /** @test **/
    public function index_should_return_a_collection_of_records()
    {
        $books = Book::factory()->count(2)->create();

        $response = $this->get('/books');

        foreach ($books as $book) {
            $response->seeJson(['title' => $book->title]);
        }
    }

    /** @test **/
    public function show_route_should_not_match_an_invalid_route()
    {
        $this->get('/books/this-is-invalid');

        $this->assertDoesNotMatchRegularExpression(
            '/Book not found/',
            $this->response->getContent(),
            'BooksController@show route matching when it should not.'
        );
    }

    /** @test **/
    public function show_should_return_a_valid_book()
    {
        // Create a book using factory
        $book = Book::factory()->create();

        // Hit the show route
        $response = $this->get("/books/{$book->id}");

        // Assert response status code
        $response->assertResponseStatus(200);

        $response->assertJson(json_encode([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
        ]));
    }

    /** @test **/
    public function show_should_fail_when_the_book_id_does_not_exist()
    {
        $this
            ->get('/books/99999', ['Accept' => 'application/json'])
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Not Found',
                    'status' => 404
                ]
            ]);
    }

    /** @test **/
    public function store_should_save_new_book_in_the_database()
    {
        $this->post('/books', [
            'title' => 'The Invisible Man',
            'description' => 'An invisible man is trapped in the terror of his own creation',
            'author' => 'H. G. Wells'
        ]);

        $this
            ->seeJson(['created' => true ])
            ->seeInDatabase('books', ['title' => 'The Invisible Man']);
    }

    /** @test */
    public function store_should_respond_with_a_201_and_location_header_when_successful()
    {
        $this->post('/books', [
            'title' => 'The Invisible Man',
            'description' => 'An invisible man is trapped in the terror of his own creation',
            'author' => 'H. G. Wells'
        ]);

        $this
            ->seeStatusCode(201)
            ->seeHeaderWithRegExp('Location', '#/books/[\d]+$#');
    }

    public function update_should_only_change_fillable_fields()
    {
        $book = Book::factory()->create([
            'title' => 'War of the Worlds',
            'description' => 'A science fiction masterpiece about Martians invading London',
            'author' => 'H. G. Wells',
        ]);

        $response = $this->put("/books/{$book->id}", [
            'title' => 'The War of the Worlds',
            'description' => 'The book is way better than the movie.',
            'author' => 'Wells, H. G.'
        ]);

        $response->assertResponseStatus(200);

        $response->assertJson(json_encode([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
        ]));

        $response->seeInDatabase('books', [
            'title' => 'The War of the Worlds'
        ]);

        // Assert the book with id 5 is not in the database
        $response->missingFromDatabase('books', [
            'id' => 5
        ]);
    }

    /** @test **/
    public function update_should_fail_with_an_invalid_id()
    {
        $this
            ->put('/books/999999999999999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    /** @test **/
    public function update_should_not_match_an_invalid_route()
    {
        $this->put('/books/this-is-invalid')
            ->seeStatusCode(404);
    }

    /** @test **/
    public function destroy_should_remove_a_valid_book()
    {
        $book = Book::factory()->create();

        $this->delete("/books/{$book->id}")
            ->assertResponseStatus(204);

        $this->missingFromDatabase('books', ['id' => $book->id]);
    }

    /** @test **/
    public function destroy_should_return_a_404_with_an_invalid_id()
    {
        $this
            ->delete('/books/99999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    /** @test **/
    public function destroy_should_not_match_an_invalid_route()
    {
        $this->delete('/books/this-is-invalid')
            ->seeStatusCode(404);
    }
}
