<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\FotoKamar;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    const HARGA_BASIC = 520000;
    const HARGA_PLUS  = 570000;

    // CREATE
    public function create()
    {
        // Hanya tampilkan fasilitas Pribadi
        $fasilitass = Fasilitas::where('tipe', 'Pribadi')->get();
        return view('admin.data.kamar.form', compact('fasilitass'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_kamar'  => 'required|string|max:10|unique:kamar,nomor_kamar',
            'tipe_kamar'   => 'required|in:Basic,Plus',
            'ukuran_kamar' => 'nullable|string|max:50',
            'status'       => 'required|in:Tersedia,Terisi,Nonaktif',
            'foto_kamar'   => 'nullable|array',
            'foto_kamar.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'fasilitas'    => 'nullable|array',
        ]);

        // Harga otomatis dari tipe — tidak dari input form
        $harga = $request->tipe_kamar === 'Plus'
                 ? self::HARGA_PLUS
                 : self::HARGA_BASIC;

        $kamar = Kamar::create([
            'nomor_kamar'  => strtoupper($request->nomor_kamar),
            'tipe_kamar'   => $request->tipe_kamar,
            'harga'        => $harga,
            'ukuran_kamar' => $request->ukuran_kamar,
            'status'       => $request->status,
        ]);

        // Simpan foto kamar
        if ($request->hasFile('foto_kamar')) {
            foreach ($request->file('foto_kamar') as $urutan => $foto) {
                $path = $foto->store('foto_kamar', 'public');
                FotoKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'foto'     => $path,
                    'urutan'   => $urutan + 1,
                ]);
            }
        }

        // Simpan relasi fasilitas Pribadi
        if ($request->filled('fasilitas')) {
            $kamar->fasilitass()->sync($request->fasilitas);
        }

        return redirect('/admin/data#kamar')
               ->with('success', 'Kamar berhasil ditambahkan.');
    }

    // EDIT
    public function edit($id)
    {
        $kamar      = Kamar::with(['fotos', 'fasilitass'])->findOrFail($id);
        $fasilitass = Fasilitas::where('tipe', 'Pribadi')->get();
        return view('admin.data.kamar.form', compact('kamar', 'fasilitass'));
    }

    public function update(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        $request->validate([
            'nomor_kamar'  => 'required|string|max:10|unique:kamar,nomor_kamar,' . $kamar->id_kamar . ',id_kamar',
            'tipe_kamar'   => 'required|in:Basic,Plus',
            'ukuran_kamar' => 'nullable|string|max:50',
            'status'       => 'required|in:Tersedia,Terisi,Nonaktif',
            'foto_kamar'   => 'nullable|array',
            'foto_kamar.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'fasilitas'    => 'nullable|array',
        ]);

        $harga = $request->tipe_kamar === 'Plus'
                 ? self::HARGA_PLUS
                 : self::HARGA_BASIC;

        $kamar->update([
            'nomor_kamar'  => strtoupper($request->nomor_kamar),
            'tipe_kamar'   => $request->tipe_kamar,
            'harga'        => $harga,
            'ukuran_kamar' => $request->ukuran_kamar,
            'status'       => $request->status,
        ]);

        // Ganti foto jika ada upload baru
        if ($request->hasFile('foto_kamar')) {
            // Hapus semua foto lama dari storage dan database
            foreach ($kamar->fotos as $foto) {
                Storage::disk('public')->delete($foto->foto);
            }
            $kamar->fotos()->delete();

            // Simpan foto baru
            foreach ($request->file('foto_kamar') as $urutan => $foto) {
                $path = $foto->store('foto_kamar', 'public');
                FotoKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'foto'     => $path,
                    'urutan'   => $urutan + 1,
                ]);
            }
        }

        // Update relasi fasilitas Pribadi
        $kamar->fasilitass()->sync($request->fasilitas ?? []);

        return redirect('/admin/data#kamar')
               ->with('success', 'Data kamar berhasil diperbarui.');
    }

    // DELETE
    public function destroy($id)
    {
        $kamar = Kamar::with('fotos')->findOrFail($id);

        // Hapus semua foto dari storage
        foreach ($kamar->fotos as $foto) {
            Storage::disk('public')->delete($foto->foto);
        }

        $kamar->delete();

        return redirect('/admin/data#kamar')
               ->with('success', 'Kamar berhasil dihapus.');
    }
}