<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Models\Pengaduan;
use App\Models\Bank;
use Carbon\Carbon; //Format Tanggal

class LayananPenyewaController extends Controller
{
    //GET Reservasi Aktif 
    private function getReservasiAktif()
    {
        $penyewa = Auth::guard('penyewa')->user();
        return Reservasi::with(['kamar'])
                        ->where('id_penyewa', $penyewa->id_penyewa)
                        ->where('status', 'Aktif')
                        ->first();
    }
    // Perpanjangan
    public function perpanjangan()
    {
        $reservasi = $this->getReservasiAktif();
        $banks     = Bank::all();
        $bulanIni   = date('Y-m-01');

        // Cek apakah bulan ini = bulan reservasi (sudah tercakup pembayaran awal)
        $bulanReservasi   = Carbon::parse($reservasi->tanggal_masuk)->format('Y-m-01');
        $isBulanReservasi = ($bulanIni === $bulanReservasi);

        //Tagihan Bulan ini
        $pembayaranBulanIni = Pembayaran::where('id_reservasi', $reservasi->id_reservasi)
                                        ->where('tipe_pembayaran', 'Perpanjangan')
                                        ->where('bulan_tagihan', $bulanIni)
                                        ->first();
        $riwayatPembayaran = Pembayaran::where('id_reservasi', $reservasi->id_reservasi)
                                       ->where('tipe_pembayaran', 'Perpanjangan')
                                       ->with('bank')
                                       ->orderBy('bulan_tagihan', 'desc')
                                       ->paginate(10);
        
        $sudahBayarBulanIni = $pembayaranBulanIni
                              && in_array($pembayaranBulanIni->status, ['Dikirim', 'Diterima']);

        $statusBayar = $pembayaranBulanIni ? $pembayaranBulanIni->status : '';                               
        return view('penyewa.perpanjangan', compact(
            'reservasi',
            'banks',
            'bulanIni',
            'pembayaranBulanIni',
            'riwayatPembayaran',
            'sudahBayarBulanIni',
            'statusBayar',
            'isBulanReservasi'
        ));
    }   

    // POST Perpanjangan
    public function storePerpanjangan(Request $request)
    {
        $request->validate([
            'id_bank'        => 'required|exists:bank,id_bank',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bulan_tagihan'  => 'required|date',
        ]);

        $reservasi = $this->getReservasiAktif();
        $bulanReservasi = Carbon::parse($reservasi->tanggal_masuk)->format('Y-m-01');
        $bulanTagihan   = Carbon::parse($request->bulan_tagihan)->format('Y-m-01');

        //Bulan yang sama tidak bisa perpanjangan
        if ($bulanTagihan === $bulanReservasi) {
            return redirect()->back() 
                    ->with('error', 'Bulan ini sudah reservasi, silahkan bayar perpanjangan di bulan berikutnya setiap tanggal 1');
            }
        
        //Cek pembayaran sudah/belum
        $cekDuplikat = Pembayaran::where('id_reservasi', $reservasi->id_reservasi)
                                  ->where('bulan_tagihan', $request->bulan_tagihan)
                                  ->where('tipe_pembayaran', 'Perpanjangan')
                                  ->whereIn('status', ['Dikirim', 'Diterima'])
                                  ->first();

        if ($cekDuplikat) {
            return redirect()->back()
                   ->with('error', 'Pembayaran untuk bulan ini sudah pernah dikirim.');
        }

        $buktiPath = $request->file('bukti_transfer')
                             ->store('bukti_bayar', 'public');
        
        Pembayaran::create([
            'id_reservasi'    => $reservasi->id_reservasi,
            'id_bank'         => $request->id_bank,
            'tipe_pembayaran' => 'Perpanjangan',
            'bulan_tagihan'   => $request->bulan_tagihan,
            'jumlah'          => $reservasi->kamar->harga,
            'bukti_transfer'  => $buktiPath,
            'tanggal_bayar'   => now(),
            'status'          => 'Dikirim',
        ]);
        return redirect('/perpanjangan')
               ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi.');
    }

    //Pengaduan
    public function pengaduan()
    {
        $reservasi  = $this->getReservasiAktif();
        $pengaduans = Pengaduan::where('id_reservasi', $reservasi->id_reservasi)
                               ->orderBy('tanggal_pengaduan', 'desc')
                               ->paginate(10);

        return view('penyewa.pengaduan', compact('reservasi', 'pengaduans'));
    }

    // GET pengaduan & Create
    public function createPengaduan()
    {
        $reservasi = $this->getReservasiAktif();
        return view('penyewa.pengaduan_create', compact('reservasi'));
    }

    // POST Pengaduan
    public function storePengaduan(Request $request)
    {
        $request->validate([
            'judul'   => 'required|string|max:150',
            'keluhan' => 'required|string',
            'foto'    => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
        $reservasi = $this->getReservasiAktif();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')
                                ->store('foto_pengaduan', 'public');
        }
        Pengaduan::create([
            'id_reservasi'     => $reservasi->id_reservasi,
            'judul'            => $request->judul,
            'keluhan'          => $request->keluhan,
            'foto'             => $fotoPath,
            'tanggal_pengaduan'=> now(),
            'status'           => 'Diajukan',
        ]);

        return redirect('/pengaduan')
               ->with('success', 'Pengaduan berhasil disampaikan.');
    }
}