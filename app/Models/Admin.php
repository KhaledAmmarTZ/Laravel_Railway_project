<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_date_of_birth',
        'admin_role',
        'admin_phoneNumber',
        'admin_place',
        'admin_password',
        'admin_nid',
    ];

    protected $hidden = [
        'admin_password',
    ];

    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}
