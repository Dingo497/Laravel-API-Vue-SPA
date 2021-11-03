<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\TodoList;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        TodoList::factory()->count(3)->create()->each(function($todoList){
          for($i = 0; $i < 4; $i++){
            $todoList->cards()->save(Card::factory()->create());
          }
        });
    }
}
