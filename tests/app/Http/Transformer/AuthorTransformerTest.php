<?php

namespace Tests;

use App\Transformer\AuthorTransformer;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Author;

class AuthorTransformerTest extends TestCase
{
    use DatabaseMigrations;

    protected AuthorTransformer $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new AuthorTransformer();
    }

    /** @test **/
    public function it_can_be_initialized()
    {
        $this->assertInstanceOf(AuthorTransformer::class, $this->subject);
    }

    /** @test **/
    public function it_can_transform_an_author()
    {
        $author = Author::factory()->create();
        $actual = $this->subject->transform($author);
        $this->assertEquals($author->id, $actual['id']);
        $this->assertEquals($author->name, $actual['name']);
        $this->assertEquals($author->gender, $actual['gender']);
        $this->assertEquals($author->biography, $actual['biography']);
        $this->assertEquals(
            $author->created_at->toIso8601String(),
            $actual['created']
        );
        $this->assertEquals(
            $author->updated_at->toIso8601String(),
            $actual['created']
        );
    }

    /** @test **/
    public function it_can_transform_related_books()
    {
        $book = $this->bookFactory();
        $author = $book->author;
        $data = $this->subject->includeBooks($author);
        $this->assertInstanceOf(\League\Fractal\Resource\Collection::class, $data);
    }
}
