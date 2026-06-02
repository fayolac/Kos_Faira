<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peraturan;

class PeraturanController extends Controller
{
    //GET Peraturan - Halaman Publik
    public function publik()
    {
        $peraturans = Peraturan::orderBy('id_peraturan')->get();
        return view('dashboard.peraturan', compact('peraturans'));
    }
    
    // CREATE Peraturan
    public function create()
    {
        return view('admin.data.peraturan.form');
    }
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'isi'   => 'required|string',
        ]);

        Peraturan::create([
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'updated_at' => now(),
        ]);

        return redirect('/admin/data#peraturan')
               ->with('success', 'Peraturan berhasil ditambahkan.');
    }

    // EDIT Peraturan
    public function edit($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        return view('admin.data.peraturan.form', compact('peraturan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'isi'   => 'required|string',
        ]);
        Peraturan::findOrFail($id)->update([
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'updated_at' => now(),
        ]);

        return redirect('/admin/data#peraturan')
               ->with('success', 'Peraturan berhasil diperbarui.');
    }

    // DELETE Peraturan
    public function destroy($id)
    {
        Peraturan::findOrFail($id)->delete();
        return redirect('/admin/data#peraturan')
               ->with('success', 'Peraturan berhasil dihapus.');
    }
}
