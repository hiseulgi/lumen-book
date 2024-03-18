<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            'title' => 'The Great Gatsby',
            'description' => 'The Great Gatsby is a 1925 novel by American writer F. Scott Fitzgerald.',
            'author' => 'F. Scott Fitzgerald',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('books')->insert([
            'title' => 'To Kill a Mockingbird',
            'description' => 'To Kill a Mockingbird is a novel by Harper Lee published in 1960.',
            'author' => 'Harper Lee',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
