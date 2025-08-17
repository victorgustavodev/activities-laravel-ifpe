<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;

class AuthorPublisherBookSeeder extends Seeder
{
    public function run()
    {
        Author::factory(100)->create()->each(function ($author) {
            $publisher = Publisher::factory()->create();

            Book::factory(10)->create([
                'author_id' => $author->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'publisher_id' => $publisher->id,
            ]);
        });
    }
}
