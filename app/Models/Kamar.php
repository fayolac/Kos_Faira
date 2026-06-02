<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table      = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'harga',
        'ukuran_kamar',
        'status',
    ];
    //One to many -> foto
    public function fotos()
    {
        return $this->hasMany(FotoKamar::class, 'id_kamar', 'id_kamar')
                    ->orderBy('urutan');
    }
    //FotoUtama
    public function fotoUtama()
    {
        return $this->hasOne(FotoKamar::class, 'id_kamar', 'id_kamar')
                    ->orderBy('urutan');
    }
    // Many to Many -> fasilitas
    public function fasilitass()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'kamar_fasilitas',
            'id_kamar',
            'id_fasilitas'
        );
    }
    // one to many -> reservasi
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'id_kamar', 'id_kamar');
    }
}
