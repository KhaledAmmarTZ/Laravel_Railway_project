<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $table = 'station';
    protected $primaryKey = 'stid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['stationname', 'city'];

    public function trainRoutes()
    {
        return $this->hasMany(TrainRoute::class, 'station_id', 'stid');
    }
}

