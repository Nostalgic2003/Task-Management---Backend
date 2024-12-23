<?php

namespace Database\Factories;

use App\Models\Lists;
use App\Models\Cards;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'position' => fake()->numberBetween(0, 100),
            'list_id' => Lists::first()->id,
        ];
    }
}
