<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table      = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_reservasi',
        'id_bank',
        'tipe_pembayaran',
        'bulan_tagihan',
        'jumlah',
        'bukti_transfer',
        'tanggal_bayar',
        'tanggal_konfirmasi',
        'status',
        'catatan_admin',
    ];
    //One to One -> Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
    //One to One -> Bank
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank', 'id_bank');
    }
}
