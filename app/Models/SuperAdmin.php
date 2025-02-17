<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class SuperAdmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'superadmin';

    protected $table = 'super_admins'; // Table name

    protected $primaryKey = 'admin_id'; // Custom primary key

    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_date_of_birth',
        'admin_role',
        'admin_phoneNumber',
        'admin_place',
        'admin_password',
        'admin_nid',
        'admin_image',
    ];

    protected $hidden = [
        'admin_password',
    ];

    protected $casts = [
        'admin_date_of_birth' => 'date',
    ];

    // Automatically hash password before storing
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = Hash::make($value);
    }

    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}
