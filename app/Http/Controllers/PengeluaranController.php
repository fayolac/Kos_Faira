<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    // GET Pengeluaran Create
    public function create() {
        return view('admin.keuangan.form');
    }
    // POST Pengeluaran
    public function store(Request $request)
    {
        $request->validate([
            'kategori'    => 'required|in:Wifi,Gas,Air,Listrik,Sampah,Pemeliharaan,Lainnya',
            'tanggal'     => 'required|date',
            'jumlah'      => 'required|numeric|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);
        Pengeluaran::create([
            'kategori'   => $request->kategori,
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/admin/keuangan')
               ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }
    //GET Pengeluaran Edit
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('admin.keuangan.form', compact('pengeluaran'));
    }
    //PUT Pengeluaran Edit
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori'    => 'required|in:Wifi,Gas,Air,Listrik,Sampah,Pemeliharaan,Lainnya',
            'tanggal'     => 'required|date',
            'jumlah'      => 'required|numeric|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update([
            'kategori'   => $request->kategori,
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);
        return redirect('/admin/keuangan')
               ->with('success', 'Pengeluaran berhasil diperbarui.');
    }
    //DELETE Pengeluaran
    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();

        return redirect('/admin/keuangan')
               ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
