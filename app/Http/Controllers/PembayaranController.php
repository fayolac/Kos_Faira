<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Reservasi;

class PembayaranController extends Controller
{
    //GET Verifikasi Pembayaran
    public function index()
    {
        $pembayarans = Pembayaran::with([
                            'reservasi.penyewa',
                            'reservasi.kamar',
                            'bank'
                        ])
                        ->orderByDesc('created_at')
                        ->paginate(10);

        return view('admin.verifikasi.index', compact('pembayarans'));
    }

    // GET Pembayaran
    public function show($id)
    {
        $pembayaran = Pembayaran::with([
                          'reservasi.penyewa',
                          'reservasi.kamar',
                          'bank'
                      ])->findOrFail($id);

        return view('admin.penyewa.detail', compact('pembayaran'));
    }

    //PUT Pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'       => 'required|in:Diterima,Ditolak',
            'catatan_admin'=> 'nullable|string|max:500',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status'             => $request->status,
            'catatan_admin'      => $request->catatan_admin,
            'tanggal_konfirmasi' => now(),
        ]);

        $reservasi = $pembayaran->reservasi;
        if ($request->status === 'Diterima'
            && $pembayaran->tipe_pembayaran === 'Reservasi') {
        $reservasiLama = Reservasi::where('id_penyewa', $reservasi->id_penyewa)
                                ->where('status', 'Aktif')
                                ->where('id_reservasi', '!=', $reservasi->id_reservasi)
                                ->first();

        if ($reservasiLama) {
            // Kembalikan kamar lama ke Tersedia dan nonaktifkan reservasi lama
            $reservasiLama->kamar->update(['status' => 'Tersedia']);
            $reservasiLama->update(['status' => 'Nonaktif']);
        }
            // Aktifkan reservasi baru
            $reservasi->update(['status' => 'Aktif']);
            $reservasi->penyewa->update(['status' => 'Aktif']);
            $reservasi->kamar->update(['status' => 'Terisi']);
        }

        //Update pembayaran reservasi ditolak
        if ($request->status === 'Ditolak'
            && $pembayaran->tipe_pembayaran === 'Reservasi') {

            $pembayaran->reservasi->update(['status' => 'Ditolak']);
            $reservasi->kamar->update(['status' => 'Tersedia']);
        }

        return redirect('/admin/verifikasi')
               ->with('success', 'Status pembayaran berhasil diverifikasi.');
    }
}
