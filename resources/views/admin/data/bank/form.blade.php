@extends('layouts.admin')

@section('title', isset($bank) ? 'Edit Rekening Bank' : 'Tambah Rekening Bank')

@section('content')

    <h1 class="page-title">{{ isset($bank) ? 'Edit Rekening Bank' : 'Tambah Rekening Bank' }}</h1>
    <p class="page-subtitle">{{ isset($bank) ? 'Ubah data rekening' : 'Tambah rekening bank baru' }}</p>

    <div class="form-admin-card">
        <form action="{{ isset($bank) ? '/admin/bank/' . $bank->id_bank : '/admin/bank' }}"
              method="POST">
            @csrf
            @if(isset($bank)) @method('PUT') @endif

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-admin-label">
                        Nama Bank <span class="required-star">*</span>
                    </label>
                    <input type="text"
                        name="nama_bank"
                        class="form-admin-control @error('nama_bank') is-invalid @enderror"
                        placeholder="Contoh: BRI, BCA, Seabank..."
                        value="{{ old('nama_bank', $bank->nama_bank ?? '') }}">
                    @error('nama_bank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-6">
                    <label class="form-admin-label">
                        Atas Nama <span class="required-star">*</span>
                    </label>
                    <input type="text" name="atas_nama"
                           class="form-admin-control @error('atas_nama') is-invalid @enderror"
                           placeholder="nama pemilik rekening"
                           value="{{ old('atas_nama', $bank->atas_nama ?? '') }}">
                    @error('atas_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-admin-label">
                    Nomor Rekening <span class="required-star">*</span>
                </label>
                <input type="text" name="nomor_rekening"
                       class="form-admin-control @error('nomor_rekening') is-invalid @enderror"
                       placeholder="XXXX XXXX XXXX XXXX"
                       value="{{ old('nomor_rekening', $bank->nomor_rekening ?? '') }}">
                @error('nomor_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:0.75rem;">
                <a href="/admin/data#bank" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">
                    {{ isset($bank) ? 'Simpan Perubahan' : 'Simpan Rekening' }}
                </button>
            </div>

        </form>
    </div>

@endsection