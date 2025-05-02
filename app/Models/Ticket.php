<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{


    protected $table = 'tickets';
    protected $primaryKey = 'ticid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['pnr', 'tclass', 'tseat', 'tcompt'];

    public function passenger()
    {
        return $this->belongsTo(Passenger::class, 'pnr', 'pnr');
    }
}
