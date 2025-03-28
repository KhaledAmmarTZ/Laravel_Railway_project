<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;
    protected $table = 'train';
    protected $primaryKey = 'trainid';
    protected $fillable = ['trainname', 'compartmentnumber', 'updownnumber', 'train_image'];

    public function traincompartments()
    {
        return $this->hasMany(Compartment::class, 'trainid', 'trainid');
    }

    public function trainupdowns()
    {
        return $this->hasMany(UpDown::class, 'trainid', 'trainid');
    }

    // public function trainRoutes()
    // {
    //     return $this->hasMany(TrainRoute::class, 'trainid', 'trainid');
    // }

}
