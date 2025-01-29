<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $table = 'train';
    protected $primaryKey = 'tid';
    protected $fillable = ['tname', 'numofcompartment'];

    // Relationships
    public function compartments()
    {
        return $this->hasMany(TNameOfCompartment::class, 'tid', 'tid');
    }

    public function deptime()
    {
        return $this->hasOne(TDepTime::class, 'tid', 'tid');
    }

    public function arrtime()
    {
        return $this->hasOne(TArrTime::class, 'tid', 'tid');
    }

    public function source()
    {
        return $this->hasOne(TSource::class, 'tid', 'tid');
    }

    public function destination()
    {
        return $this->hasOne(TDesti::class, 'tid', 'tid');
    }
}
