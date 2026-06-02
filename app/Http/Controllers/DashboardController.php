<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Bank;

class DashboardController extends Controller
{
    // Harga fixed per tipe — tidak perlu query ke DB
    const HARGA_BASIC = 520000;
    const HARGA_PLUS  = 570000;

    // =============================================
    // Halaman Landing / Beranda
    // =============================================
    public function index()
    {
        $kamarBasic = Kamar::with('fotoUtama')
                           ->where('tipe_kamar', 'Basic')
                           ->where('status', '!=', 'Nonaktif')
                           ->first();

        $kamarPlus = Kamar::with('fotoUtama')
                          ->where('tipe_kamar', 'Plus')
                          ->where('status', '!=', 'Nonaktif')
                          ->first();

        $fasilitasBersama = Fasilitas::where('tipe', 'Bersama')
                                     ->take(4)
                                     ->get();

        return view('dashboard.index', [
            'kamarBasic'      => $kamarBasic,
            'kamarPlus'       => $kamarPlus,
            'fasilitasBersama'=> $fasilitasBersama,
            'hargaBasic'      => self::HARGA_BASIC,
            'hargaPlus'       => self::HARGA_PLUS,
        ]);
    }

    // =============================================
    // Halaman Kamar & Fasilitas
    // =============================================
    public function kamarFasilitas()
    {
        $kamarBasic = Kamar::with('fotoUtama')
                           ->where('tipe_kamar', 'Basic')
                           ->where('status', '!=', 'Nonaktif')
                           ->first();

        $kamarPlus = Kamar::with('fotoUtama')
                          ->where('tipe_kamar', 'Plus')
                          ->where('status', '!=', 'Nonaktif')
                          ->first();

        $fasilitasBersama = Fasilitas::where('tipe', 'Bersama')->get();

        return view('dashboard.kamar_fasilitas', [
            'kamarBasic'      => $kamarBasic,
            'kamarPlus'       => $kamarPlus,
            'fasilitasBersama'=> $fasilitasBersama,
            'hargaBasic'      => self::HARGA_BASIC,
            'hargaPlus'       => self::HARGA_PLUS,
        ]);
    }

    // =============================================
    // Halaman Detail Kamar per Tipe
    // =============================================
    public function detailKamar($tipe)
    {
        // Validasi tipe
        if (!in_array($tipe, ['Basic', 'Plus'])) {
            abort(404);
        }

        // Ambil data kamar pertama tipe ini untuk info umum
        $kamar = Kamar::with(['fotos', 'fasilitass'])
                      ->where('tipe_kamar', $tipe)
                      ->where('status', '!=', 'Nonaktif')
                      ->first();

        if (!$kamar) {
            abort(404);
        }

        // Kamar yang benar-benar bisa dipesan:
        // status Tersedia DAN tidak punya reservasi Aktif/Menunggu
        $kamarTersedia = Kamar::where('tipe_kamar', $tipe)
                              ->where('status', 'Tersedia')
                              ->whereDoesntHave('reservasis', function ($q) {
                                  $q->whereIn('status', ['Aktif', 'Menunggu']);
                              })
                              ->get();

        // Semua kamar tipe ini (kecuali Nonaktif) untuk ditampilkan di grid
        $semuaKamar = Kamar::where('tipe_kamar', $tipe)
                           ->where('status', '!=', 'Nonaktif')
                           ->orderBy('nomor_kamar')
                           -> with('fotos')
                           ->get();

        // Fasilitas bersama untuk semua penghuni
        $fasilitasBersama = Fasilitas::where('tipe', 'Bersama')->get();

        // Harga fixed sesuai tipe
        $harga = $tipe === 'Plus' ? self::HARGA_PLUS : self::HARGA_BASIC;

        // Info bank untuk ditampilkan di halaman detail
        $banks = Bank::all();

        return view('dashboard.detail_kamar', [
            'kamar'           => $kamar,
            'kamarTersedia'   => $kamarTersedia,
            'semuaKamar'      => $semuaKamar,
            'fasilitasBersama'=> $fasilitasBersama,
            'tipe'            => $tipe,
            'harga'           => $harga,
            'banks'           => $banks,
        ]);
    }

    // =============================================
    // Halaman Peraturan Publik
    // =============================================
    public function peraturan()
    {
        $peraturans = \App\Models\Peraturan::orderBy('id_peraturan')->get();
        return view('dashboard.peraturan', compact('peraturans'));
    }
}