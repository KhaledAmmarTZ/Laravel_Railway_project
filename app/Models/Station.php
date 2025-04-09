<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $table = 'station'; // Ensure the table name is correct
    protected $primaryKey = 'stid'; // Set the primary key if it's not 'id'
    
    protected $fillable = ['stationname', 'city']; // Add relevant columns

    public function trainRoutes()
    {
        return $this->hasMany(TrainRoute::class, 'station_id', 'stid');
    }
}

