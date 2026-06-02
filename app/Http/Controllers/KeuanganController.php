<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class KeuanganController extends Controller
{
    public function index()
    {
        // Rekap Pemasukan
        $pemasukans = Pembayaran::with(['reservasi.penyewa', 'reservasi.kamar', 'bank'])
                                ->where('status', 'Diterima')
                                ->orderBy('tanggal_konfirmasi', 'desc')
                                ->paginate(10);
        // Rekap Pengeluaran
        $pengeluarans = Pengeluaran::orderBy('tanggal', 'desc')->paginate(10);
        
        //Card Rekap
        $totalPemasukan = Pembayaran::where('status', 'Diterima')->sum('jumlah');
        $totalPengeluaran = $pengeluarans->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        return view('admin.keuangan.index', compact(
            'pemasukans',
            'pengeluarans',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBersih'
        ));
    }
}
