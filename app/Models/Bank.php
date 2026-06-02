<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table      = 'bank';
    protected $primaryKey = 'id_bank';

    protected $fillable = [
        'nama_bank',
        'atas_nama',
        'nomor_rekening',
    ];
    // One to Many -> Pembayaran
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_bank', 'id_bank');
    }
}
