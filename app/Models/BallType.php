<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BallType extends Model
{
    use HasFactory;

    protected $table = 'ball_type_info';

    protected $fillable = ['type', 'game_name', 'cht_game_name'];

    public $timestamps = false;

}
