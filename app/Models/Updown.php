<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class updown extends Model
{
    use HasFactory;
    protected $table = 'trainupdowns';
    protected $primaryKey = 'id';
    protected $fillable = ['trainid', 'tarrtime', 'tdeptime', 'tarrdate', 'tdepdate', 'tsource', 'tdestination', 'sequence'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'trainid', 'trainid');
    }
}
