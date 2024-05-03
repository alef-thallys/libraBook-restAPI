<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $quantity = $this->faker->numberBetween(0, 5);
        $available = $quantity > 0 ? '1' : '0';

        return [
            'book_id' => Book::factory()->create()->id,
            'available' => $available,
            'quantity' => $quantity,
        ];
    }
}
