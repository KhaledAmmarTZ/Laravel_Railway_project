<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $table = 'stations'; // Ensure the table name is correct
    protected $fillable = ['stationname', 'deeptime', 'artime']; // Add relevant columns
}

