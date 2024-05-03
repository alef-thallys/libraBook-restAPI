<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'published_year',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
