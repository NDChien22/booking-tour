<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasFactory;
    protected $primaryKey = 'region_id';

    protected $fillable = [
        'region_name'
    ];

    public function cities()
    {
        return $this->hasMany(Cities::class, 'region_id');
    }
}
