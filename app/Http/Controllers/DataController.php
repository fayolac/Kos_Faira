<?php

namespace App\Http\Controllers;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Peraturan;
use App\Models\Bank;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $kamars     = Kamar::with('fotoUtama')->orderBy('nomor_kamar')->paginate(10, ['*'], 'kamar_page');
        //$fasilitass  = Fasilitas::with('kamars')->orderBy('tipe')->paginate(10, ['*'], 'fasilitas_page');
        $fasilitasBersama = Fasilitas::where('tipe', 'Bersama')
                                  ->paginate(5, ['*'], 'bersama_page');

        $fasilitasPribadi = Fasilitas::with('kamars')
                                  ->where('tipe', 'Pribadi')
                                  ->paginate(5, ['*'], 'pribadi_page');
        $peraturans = Peraturan::orderBy('id_peraturan')->paginate(10, ['*'], 'peraturan_page');
        $banks      = Bank::orderBy('nama_bank')->paginate(10, ['*'], 'bank_page');

        return view('admin.data.index', compact(
            'kamars', 'fasilitasBersama', 'fasilitasPribadi', 'peraturans', 'banks'
        ));
    }
}
