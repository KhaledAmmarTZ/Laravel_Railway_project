<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TArrTime extends Model
{
    use HasFactory;

    protected $table = 'tarrtime';
    protected $fillable = ['tid', 'arrtime'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'tid', 'tid');
    }
}
