<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    // CREATE BANK
    public function create()
    {
        return view('admin.data.bank.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:30',
        ]);

        Bank::create($request->only('nama_bank', 'atas_nama', 'nomor_rekening'));

        return redirect('/admin/data#bank')
               ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    //EDIT Bank
    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.data.bank.form', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:30',
        ]);

        Bank::findOrFail($id)->update(
            $request->only('nama_bank', 'atas_nama', 'nomor_rekening')
        );

        return redirect('/admin/data#bank')
               ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    //DELETE Bank
    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();
        return redirect('/admin/data#bank')
               ->with('success', 'Rekening bank berhasil dihapus.');
    }
}
