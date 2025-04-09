<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainRoute extends Model
{
    use HasFactory;

    protected $table = 'train_routes';
    protected $fillable = ['trainid', 'station_id', 'sequence'];

    // Relationship with Train
    public function train()
    {
        return $this->belongsTo(Train::class, 'trainid', 'trainid');
    }

    // Relationship with Station
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'stid');
    }
}
