<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table      = 'reservasi';
    protected $primaryKey = 'id_reservasi';

    protected $fillable = [
        'id_penyewa',
        'id_kamar',
        'tanggal_reservasi',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'catatan',
    ];
    // One to One -> Penyewa
    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'id_penyewa', 'id_penyewa');
    }
    // One to One -> Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
    //One to Many -> Pembayaran 
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }
    //One to Many ->Pengaduan
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_reservasi', 'id_reservasi');
    }
    
}
