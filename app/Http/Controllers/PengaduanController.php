<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PengaduanController extends Controller
{
    // GET Pengaduan
    public function index()
    {
        $pengaduans = Pengaduan::with(['reservasi.penyewa', 'reservasi.kamar'])
                               ->orderBy('tanggal_pengaduan', 'desc')
                               ->paginate(10);

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    //GET Edit Pengaduan
    public function edit($id)
    {
        $pengaduan = Pengaduan::with(['reservasi.penyewa', 'reservasi.kamar'])
                              ->findOrFail($id);

        return view('admin.pengaduan.form', compact('pengaduan'));
    }

    //PUT Pengaduan
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'           => 'required|in:Diajukan,Diproses,Selesai',
            'tanggapan_admin'  => 'nullable|string|max:1000',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'status'          => $request->status,
            'tanggapan_admin' => $request->tanggapan_admin,
            'tanggal_update'  => now(),
        ]);
        return redirect('/admin/pengaduan')
               ->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
