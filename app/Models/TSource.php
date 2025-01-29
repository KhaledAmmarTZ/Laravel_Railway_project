<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSource extends Model
{
    use HasFactory;

    protected $table = 'tsource';
    protected $fillable = ['tid', 'source'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'tid', 'tid');
    }
}
