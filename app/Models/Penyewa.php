<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Penyewa extends Authenticatable
{
    protected $table      = 'penyewa';
    protected $primaryKey = 'id_penyewa';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telp',
        'pekerjaan',
        'agama',
        'foto_ktp',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi OneToMany
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'id_penyewa', 'id_penyewa');
    }

    public function reservasiAktif()
    {
        return $this->hasOne(Reservasi::class, 'id_penyewa', 'id_penyewa')
                    ->where('status', 'Aktif');
    }
}
