<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'user_id',
        'content',
        'respond'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
