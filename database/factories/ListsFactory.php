<?php

namespace Database\Factories;

use App\Models\Lists;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lists>
 */
class ListsFactory extends Factory
{
    protected $model = Lists::class;

  public function definition()
    {
        return [
            'name' => fake()->words(2, true),
            'position' => fake()->numberBetween(0, 100),
            'board_id' => Board::first()->id  // Use an existing board
        ];
    }
}
