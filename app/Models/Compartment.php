<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compartment extends Model
{
    use HasFactory;
    protected $table = 'traincompartments';
    protected $primaryKey = 'id';
    protected $fillable = ['trainid', 'seatnumber', 'compartmentname'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'trainid', 'trainid');
    }
    
}
