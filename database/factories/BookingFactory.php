<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $availableStatus = ['pending', 'confirmed', 'rejected', 'borrowed', 'returned'];
        $status = $availableStatus[random_int(0, 4)];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'book_id' => Book::inRandomOrder()->first()->id,

            'status' => $status,
            'borrowed_at' => $status === 'borrowed' ? now() : null,
            'due_date' => $status === 'borrowed' ? now()->addDay(14) : null,
            'returned_at' => $status === 'returned' ? now() : null,
        ];
    }
}
