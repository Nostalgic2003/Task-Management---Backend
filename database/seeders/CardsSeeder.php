<?php

namespace Database\Seeders;

use App\Models\Cards;
use App\Models\Lists;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = Lists::all();

        foreach ($lists as $list) {
            // Create 3-7 cards per list with sequential positions
            $numCards = rand(3, 7);
            for ($i = 0; $i < $numCards; $i++) {
                Cards::factory()->create([
                    'list_id' => $list->id,
                    'position' => $i
                ]);
            }
        }
    }
}
