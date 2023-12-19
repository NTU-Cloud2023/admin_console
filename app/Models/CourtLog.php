<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtLog extends Model
{
    use HasFactory;

    protected $table = 'court_log';

    protected $fillable = ['court_id', 'user_id', 'order_id', 'timeslot'];
}
