<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDepTime extends Model
{
    use HasFactory;

    protected $table = 'tdeptime';
    protected $fillable = ['tid', 'deptime'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'tid', 'tid');
    }
}
