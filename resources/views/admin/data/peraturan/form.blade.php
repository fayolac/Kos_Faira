@extends('layouts.admin')

@section('title', isset($peraturan) ? 'Edit Peraturan' : 'Tambah Peraturan')

@section('content')

    <h1 class="page-title">{{ isset($peraturan) ? 'Edit Peraturan' : 'Tambah Peraturan' }}</h1>
    <p class="page-subtitle">{{ isset($peraturan) ? 'Ubah peraturan kos' : 'Tambah peraturan kos baru' }}</p>

    <div class="form-admin-card">
        <form action="{{ isset($peraturan) ? '/admin/peraturan/' . $peraturan->id_peraturan : '/admin/peraturan' }}"
              method="POST">
            @csrf
            @if(isset($peraturan)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-admin-label">
                    Judul Peraturan <span class="required-star">*</span>
                </label>
                <input type="text" name="judul"
                       class="form-admin-control @error('judul') is-invalid @enderror"
                       placeholder="Contoh: Jam malam, Kebersihan..."
                       value="{{ old('judul', $peraturan->judul ?? '') }}">
                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-admin-label">
                    Isi Peraturan <span class="required-star">*</span>
                </label>
                <textarea name="isi" rows="5"
                          class="form-admin-control @error('isi') is-invalid @enderror"
                          placeholder="Tulis isi peraturan secara lengkap...">{{ old('isi', $peraturan->isi ?? '') }}</textarea>
                @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:0.75rem;">
                <a href="/admin/data#peraturan" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">
                    {{ isset($peraturan) ? 'Simpan Perubahan' : 'Tambah Peraturan' }}
                </button>
            </div>

        </form>
    </div>

@endsection