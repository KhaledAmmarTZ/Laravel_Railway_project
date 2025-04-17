<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    use Notifiable;
    protected $primaryKey = 'pnr';   
    public $timestamps = false;
    protected $fillable = [
        'dpdate', 'arrdate', 'arrtime', 'mealop', 'user_id', 'tsource', 'tdest', 'pstatus', 'tclass', 'price'
    ];

    public function train(): BelongsTo
    {
        return $this->belongsTo(Train::class, 'trainid'); 
    }
}
