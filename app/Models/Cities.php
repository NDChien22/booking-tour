<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $primaryKey = 'city_id';

    protected $fillable = [
        'city_name',
        'region_id',
    ];

    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'city_id');
    }
}
