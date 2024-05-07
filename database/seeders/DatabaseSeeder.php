<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Booking;
use App\Models\Fine;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@localhost',
            'password' => Hash::make('12345678Abc'),
            'role' => 'admin',
        ]);

        Stock::factory(10)->create();
        Booking::factory(9)->create();
        Fine::factory(10)->create();
    }
}
