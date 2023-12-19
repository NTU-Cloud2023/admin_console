<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    public function adjustToPreviousHour($timestamp) {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        $date->setTime($date->format('H'), 0, 0);
        return $date->getTimestamp();
    }


    protected $table = 'court_info';


    protected $fillable = ['name', 'latitude', 'longitude', 'capacity', 'type', 'address', 'eachtime', 'in_game', 'pic' ];


    public function ballType()
    {
        return $this->belongsTo(BallType::class, 'type', 'type');
    }

    public function CourtCurrents()
    {
        return $this->hasMany(CourtCurrent::class, 'court_id', 'court_id');
    }
}
