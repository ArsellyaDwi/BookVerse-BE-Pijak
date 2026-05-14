<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookQuoteSeeder extends Seeder
{
    public function run()
    {
        $quotes = [
            ['book_id' => 1, 'quote' => 'The Alchemist: "When you want something, all the universe conspires in helping you to achieve it."', 'mood' => 'hope', 'page_number' => 22],
            ['book_id' => 1, 'quote' => 'The Alchemist: "It\'s the possibility of having a dream come true that makes life interesting."', 'mood' => 'excitement', 'page_number' => 11],
            ['book_id' => 2, 'quote' => 'Divergent: "Becoming fearless isn\'t the point. That\'s impossible. It\'s learning how to control your fear."', 'mood' => 'courage', 'page_number' => 87],
            ['book_id' => 2, 'quote' => 'Divergent: "We believe in ordinary acts of bravery, in the courage that drives one person to stand up for another."', 'mood' => 'bravery', 'page_number' => 156],
            ['book_id' => 3, 'quote' => 'The Hunger Games: "Happy Hunger Games! And may the odds be ever in your favor."', 'mood' => 'fear', 'page_number' => 18],
            ['book_id' => 3, 'quote' => 'The Hunger Games: "Fire is catching! And if we burn, you burn with us!"', 'mood' => 'anger', 'page_number' => 257],
            ['book_id' => 4, 'quote' => 'Harry Potter: "Happiness can be found, even in the darkest of times, if one only remembers to turn on the light."', 'mood' => 'hope', 'page_number' => 123],
            ['book_id' => 4, 'quote' => 'Harry Potter: "It does not do to dwell on dreams and forget to live."', 'mood' => 'wisdom', 'page_number' => 56],
            ['book_id' => 4, 'quote' => 'Harry Potter: "Fear of a name increases fear of the thing itself."', 'mood' => 'fear', 'page_number' => 89],
            ['book_id' => 5, 'quote' => 'Twilight: "And so the lion fell in love with the lamb."', 'mood' => 'love', 'page_number' => 98],
            ['book_id' => 5, 'quote' => 'Twilight: "Death is peaceful, easy. Life is harder."', 'mood' => 'sadness', 'page_number' => 234],
            ['book_id' => 6, 'quote' => 'The Hobbit: "There is nothing like looking, if you want to find something."', 'mood' => 'curiosity', 'page_number' => 45],
            ['book_id' => 6, 'quote' => 'The Hobbit: "Go back? No good at all! Go sideways? Impossible! Go forward? Only thing to do!"', 'mood' => 'determination', 'page_number' => 89],
        ];

        DB::table('book_quotes')->insert($quotes);
    }
}