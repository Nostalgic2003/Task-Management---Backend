<?php

namespace Database\Seeders;

use App\Models\Lists;
use App\Models\Board;
use Illuminate\Database\Seeder;

class ListsSeeder extends Seeder
{
    public function run()
    {
        // Create lists for the first board
        $firstBoard = Board::first();
        
        Lists::create([
            'name' => 'To Do',
            'position' => 0,
            'board_id' => $firstBoard->id
        ]);

        Lists::create([
            'name' => 'In Progress',
            'position' => 1,
            'board_id' => $firstBoard->id
        ]);

        Lists::create([
            'name' => 'Done',
            'position' => 2,
            'board_id' => $firstBoard->id
        ]);

        // Create random lists for other boards
        Lists::factory(5)->create();
    }
}
