<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtCurrent extends Model
{
    use HasFactory;

    protected $table = 'court_current_info';


    protected $fillable = ['court_id', 'headcount', 'timestamp'];

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id', 'id');
    }
}
