<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $primaryKey = 'tour_id';

    protected $fillable = [
        'title',
        'description',
        'url_img',
        'itinerary',
        'duration',
        'price',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }
}
