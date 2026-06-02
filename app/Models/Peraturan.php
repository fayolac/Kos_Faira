<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table      = 'peraturan';
    protected $primaryKey = 'id_peraturan';
    const CREATED_AT = null;

    protected $fillable = [
        'judul',
        'isi',
    ];
}
