<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Models\Book;
use App\Models\Author;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * See if the response has a header.
     *
     * @param $header
     * @return $this
     */
    public function seeHasHeader($header)
    {
        $this->assertTrue(
            $this->response->headers->has($header),
            "Response should have the header ' { $header } ' but does not."
        );

        return $this;
    }

    /**
     * Asserts that the response header matches a given regular expression
     *
     * @param $header
     * @param $regexp
     * @return $this
     */
    public function seeHeaderWithRegExp($header, $regexp)
    {
        $this
            ->seeHasHeader($header)
            ->assertMatchesRegularExpression(
                $regexp,
                $this->response->headers->get($header)
            );

        return $this;
    }

    /**
     * Convenience method for creating a book with an author
     *
     * @param int $count
     * @return mixed
     */
    protected function bookFactory($count = 1)
    {
        $author = Author::factory()->create();

        $books = Book::factory()->count($count)->create([
            'author_id' => $author->id,
        ]);

        return $count === 1 ? $books->first() : $books;
    }
}
