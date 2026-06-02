<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyewa;
use App\Models\Reservasi;

class PenyewaController extends Controller
{
    // GET Penyewa
    public function index()
    {
        $penyewas = Penyewa::whereHas('reservasis')
                           ->orderByRaw("FIELD(status, 'Aktif', 'Nonaktif')") 
                           ->orderByDesc('created_at')
                           ->paginate(10);

        return view('admin.penyewa.index', compact('penyewas'));
    }

    // GET Penyewa Edit
    public function edit($id) 
    {
        $penyewa = Penyewa::with(['reservasis.kamar'])
                          ->findOrFail($id);

        // Ambil reservasi aktif atau terakhir
        $reservasiAktif = $penyewa->reservasis
                                  ->where('status', 'Aktif')
                                  ->first()
                          ?? $penyewa->reservasis->sortByDesc('created_at')->first();

        return view('admin.penyewa.form', compact('penyewa', 'reservasiAktif'));
    }

    //PUT Penyewa update
    public function update(Request $request, $id) 
    {
       $request->validate([
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $penyewa = Penyewa::findOrFail($id);
        $statusLama = $penyewa->status;
        $penyewa->update(['status' => $request->status]);

        //Ubah jadi Nonaktif
        if ($request->status === 'Nonaktif' && $statusLama === 'Aktif') {

            $reservasiAktif = $penyewa->reservasis()
                                    ->where('status', 'Aktif')
                                    ->first();

            if ($reservasiAktif) {
                // Kamar kembali Tersedia
                $reservasiAktif->kamar->update(['status' => 'Tersedia']);
                $reservasiAktif->update(['status' => 'Nonaktif']);
            }
        }

        //Ubah jadi Aktif
        if ($request->status === 'Aktif' && $statusLama === 'Nonaktif') {

            $reservasiTerakhir = $penyewa->reservasis()
                                        ->where('status', 'Nonaktif')
                                        ->latest()
                                        ->first();

            if ($reservasiTerakhir) {
                // Kamar jadi Terisi kembali
                $reservasiTerakhir->kamar->update(['status' => 'Terisi']);
                $reservasiTerakhir->update(['status' => 'Aktif']);
            }
        }

        return redirect('/admin/penyewa')
               ->with('success', 'Status penyewa berhasil diperbarui.'); 
    }
}
