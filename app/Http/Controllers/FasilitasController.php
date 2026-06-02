<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Kamar;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    // CREATE Fasilitas
    public function create()
    {
        $kamars = Kamar::orderBy('nomor_kamar')->get();
        return view('admin.data.fasilitas.form', compact('kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:100',
            'tipe'           => 'required|in:Bersama,Pribadi',
            //'ikon'           => 'nullable|string|max:50',
            'deskripsi'      => 'nullable|string|max:255',
            'foto'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'kamars'         => 'nullable|array',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_fasilitas', 'public');
        }

        $fasilitas = Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'tipe'           => $request->tipe,
            //'ikon'           => $request->ikon,
            'deskripsi'      => $request->deskripsi,
            'foto'           => $fotoPath,
        ]);

        // Relasi ke kamar (untuk fasilitas Pribadi)
        if ($request->tipe === 'Pribadi' && $request->filled('kamars')) {
            $fasilitas->kamars()->sync($request->kamars);
        }

        return redirect('/admin/data#fasilitas')
               ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    //EDIT Fasiltas
    public function edit($id)
    {
        $fasilitas = Fasilitas::with('kamars')->findOrFail($id);
        $kamars    = Kamar::orderBy('nomor_kamar')->get();
        return view('admin.data.fasilitas.form', compact('fasilitas', 'kamars'));
    }

    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        $request->validate([
            'nama_fasilitas' => 'required|string|max:100',
            'tipe'           => 'required|in:Bersama,Pribadi',
            //'ikon'           => 'nullable|string|max:50',
            'deskripsi'      => 'nullable|string|max:255',
            'foto'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'kamars'         => 'nullable|array',
        ]);
        $fotoPath = $fasilitas->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) Storage::disk('public')->delete($fotoPath);
            $fotoPath = $request->file('foto')->store('foto_fasilitas', 'public');
        }

        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'tipe'           => $request->tipe,
            //'ikon'           => $request->ikon,
            'deskripsi'      => $request->deskripsi,
            'foto'           => $fotoPath,
        ]);

        // Sinkronisasi fasilitas dengan kamar
        if ($request->tipe === 'Pribadi') {
            $fasilitas->kamars()->sync($request->kamars ?? []);
        } else {
            // Fasilitas Bersama tidak perlu relasi ke kamar tertentu
            $fasilitas->kamars()->detach();
        }

        return redirect('/admin/data#fasilitas')
               ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    //DELETE Fasilitas
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        if ($fasilitas->foto) {
            Storage::disk('public')->delete($fasilitas->foto);
        }
        $fasilitas->delete();

        return redirect('/admin/data#fasilitas')
               ->with('success', 'Fasilitas berhasil dihapus.');
    } 
}
