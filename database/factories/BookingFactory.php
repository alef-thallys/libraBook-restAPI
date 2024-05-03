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
        $availableStatus = ['active', 'overdue'];
        $status = $availableStatus[random_int(0, count($availableStatus) - 1)];

        $borrowed_at = null;
        $due_date = null;
        $returned_at = null;

        switch ($status) {
            case 'active':
                $borrowed_at = now();
                $due_date = now()->addDay(20);
                break;
            case 'overdue':
                $borrowed_at = now()->subDay(20);
                $due_date = now()->addDay(22);
                break;
        }

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'book_id' => Book::inRandomOrder()->first()->id,

            'borrowed_at' => $borrowed_at,
            'due_date' => $due_date,
            'returned_at' => $returned_at,
        ];

        // return [
        //     'user_id' => User::inRandomOrder()->first()->id,
        //     'book_id' => Book::inRandomOrder()->first()->id,
        // ];
    }
}
