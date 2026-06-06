<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        //Filter
        $tahunPemasukan = Pembayaran::where('status', 'Diterima')
            ->whereNotNull('tanggal_konfirmasi')
            ->selectRaw('YEAR(tanggal_konfirmasi) as tahun');
 
        $tahunList = Pengeluaran::selectRaw('YEAR(tanggal) as tahun')
            ->union($tahunPemasukan)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->unique()
            ->values();
        if (!$tahunList->contains(now()->year)) {
            $tahunList = $tahunList->prepend(now()->year)->sortDesc()->values();
        }
        $tahunDipilih = (int) $request->get('tahun', now()->year);

        // Rekap Pemasukan
        $pemasukans = Pembayaran::with(['reservasi.penyewa', 'reservasi.kamar', 'bank'])
                                ->where('status', 'Diterima')
                                ->whereYear('tanggal_konfirmasi', $tahunDipilih)
                                ->orderBy('tanggal_konfirmasi', 'desc')
                                ->paginate(10)
                                ->withQueryString();

        // Rekap Pengeluaran
        $pengeluarans = Pengeluaran::whereYear('tanggal', $tahunDipilih)
                                ->orderBy('tanggal', 'desc')
                                ->paginate(10)
                                ->withQueryString();
                                
        
        //Card Rekap
        $totalPemasukan = Pembayaran::where('status', 'Diterima')->whereYear('tanggal_konfirmasi', $tahunDipilih)->sum('jumlah');
        $totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahunDipilih)->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        return view('admin.keuangan.index', compact(
            'pemasukans',
            'pengeluarans',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBersih',
            'tahunList',
            'tahunDipilih'
        ));
    }
}
