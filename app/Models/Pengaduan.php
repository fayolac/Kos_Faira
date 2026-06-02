<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table      = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';

    protected $fillable = [
        'id_reservasi',
        'judul',
        'keluhan',
        'foto',
        'tanggal_pengaduan',
        'status',
        'tanggapan_admin',
        'tanggal_update',
    ];
    //One to One -> Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
    //Ambil data penyewa dari Reservasi
    public function penyewa()
    {
        return $this->reservasi->penyewa;
    }
}
