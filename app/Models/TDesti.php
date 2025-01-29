<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDesti extends Model
{
    use HasFactory;

    protected $table = 'tdesti';
    protected $fillable = ['tid', 'destination'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'tid', 'tid');
    }
}
