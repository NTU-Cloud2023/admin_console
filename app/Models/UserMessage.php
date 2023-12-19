<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    use HasFactory;

    protected $table = 'message_history';

    protected $fillable = [
        'user_id', 
        'message', 
        'message_timestamp',
        'viewed'
    ];

}
