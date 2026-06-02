<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Models\Bank;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use Carbon\Carbon; //Format Tanggal

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        // Cek sudah login sebagai penyewa
        if (!Auth::guard('penyewa')->check()) {
            return redirect('/login')
                   ->with('error', 'Silakan login terlebih dahulu untuk melakukan reservasi.');
        }
        $idKamar = $request->query('id_kamar');
        $kamar   = Kamar::find($idKamar);

        if (!$kamar) {
            return redirect('/kamar')
                   ->with('error', 'Kamar tidak ditemukan.');
        }

        if ($kamar->status !== 'Tersedia') {
            return redirect('/kamar/' . $kamar->tipe_kamar)
                   ->with('error', 'Kamar ini sudah tidak tersedia.');
        }
        $penyewa          = Auth::guard('penyewa')->user();
        $reservasiAktif   = Reservasi::where('id_penyewa', $penyewa->id_penyewa)
                                     ->whereIn('status', ['Aktif', 'Menunggu'])
                                     ->first();

        if ($reservasiAktif) {
            return redirect('/')
                   ->with('error', 'Anda sudah memiliki reservasi aktif atau sedang menunggu konfirmasi.');
        }
        $banks = Bank::all();

        return view('penyewa.reservasi', compact('kamar', 'banks'));
    }

    //POST 
    public function store(Request $request)
    {
        $request->validate([
            'id_kamar'       => 'required|exists:kamar,id_kamar',
            'tanggal_masuk'  => 'required|date|after_or_equal:today',
            'id_bank'        => 'required|exists:bank,id_bank',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'setuju_peraturan'  => 'required|accepted',
        ]);

        $penyewa = Auth::guard('penyewa')->user();
        $kamar   = Kamar::find($request->id_kamar);

        $buktiPath = $request->file('bukti_transfer')
                             ->store('bukti_bayar', 'public');

        // Buat reservasi
        $reservasi = Reservasi::create([
            'id_penyewa'        => $penyewa->id_penyewa,
            'id_kamar'          => $request->id_kamar,
            'tanggal_reservasi' => now(),
            'tanggal_masuk'     => $request->tanggal_masuk,
            'status'            => 'Menunggu',
        ]);
        Pembayaran::create([
            'id_reservasi'   => $reservasi->id_reservasi,
            'id_bank'        => $request->id_bank,
            'tipe_pembayaran'=> 'Reservasi',
            'bulan_tagihan'  => date('Y-m-01', strtotime($request->tanggal_masuk)),
            'jumlah'         => $kamar->harga,
            'bukti_transfer' => $buktiPath,
            'tanggal_bayar'  => now(),
            'status'         => 'Dikirim',
        ]);
        return redirect('/cek-status')
               ->with('success', 'Reservasi berhasil diajukan. Menunggu konfirmasi pemilik kos.');
    }

    //Cek Status GET
    public function cekStatus()
    {
        $penyewa = Auth::guard('penyewa')->user();

        // Ambil reservasi terbaru penyewa
        $reservasi = Reservasi::with(['kamar', 'pembayarans.bank'])
                              ->where('id_penyewa', $penyewa->id_penyewa)
                              ->latest()
                              ->first();
        $pembayaran = null;
        if ($reservasi) {
            $pembayaran = $reservasi->pembayarans()
                                    ->where('tipe_pembayaran', 'Reservasi')
                                    ->latest()
                                    ->first();
        }

        return view('penyewa.cek_status', compact('reservasi', 'pembayaran', 'penyewa'));
    }
}



