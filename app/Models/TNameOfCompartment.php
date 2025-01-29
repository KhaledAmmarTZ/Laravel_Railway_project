<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNameOfCompartment extends Model
{
    use HasFactory;

    protected $table = 'tnameofcompartment';
    protected $fillable = ['tid', 'nameofeachcompartment', 'numofseat'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'tid', 'tid');
    }
}
