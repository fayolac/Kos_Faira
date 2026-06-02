<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoKamar extends Model
{
    protected $table      = 'foto_kamar';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_kamar',
        'foto',
        'urutan',
    ];
    // Many to One -> Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
