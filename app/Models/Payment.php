<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payid';
    public $timestamps = false;

    protected $fillable = [
        'pdate',
        'pamount',
        'pmethod',
        'ticket_count',
        'mealop',
        'uid',
    ];
}