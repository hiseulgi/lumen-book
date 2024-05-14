<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory(10)->create()->each(function ($author) {
            $booksCount = rand(1, 5);
            while ($booksCount > 0) {
                $author->books()->save(Book::factory()->make());
                $booksCount--;
            }
        });
    }
}
