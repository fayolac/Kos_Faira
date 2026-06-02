<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table      = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telp',
    ];

    protected $hidden = [
        'password',
    ];
}
