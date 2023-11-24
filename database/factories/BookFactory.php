<?php

namespace Database\Factories;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $author = DB::table('authors')->select(['id'])->get();
        return [
            'title' => $this->faker->sentence(random_int(6, 10)),
            'author_id' => $author[random_int(1, $author->count() - 1)]->id,
            'publication_year' => Carbon::now()->subYears(rand(1, 100))->format('Y'),
            'isbn' => $this->faker->isbn13()
        ];
    }
}
