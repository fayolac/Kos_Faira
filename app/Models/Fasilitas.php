<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table      = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';

    protected $fillable = [
        'nama_fasilitas',
        'ikon',
        'deskripsi',
        'tipe',
        'foto',
    ];
    // Many to Many -> Kamar
    public function kamars()
    {
        return $this->belongsToMany(
            Kamar::class,
            'kamar_fasilitas',
            'id_fasilitas',
            'id_kamar'
        );
    }
}
