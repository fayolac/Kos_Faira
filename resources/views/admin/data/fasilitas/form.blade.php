@extends('layouts.admin')

@section('title', isset($fasilitas) ? 'Edit Fasilitas' : 'Tambah Fasilitas')

@section('content')

    <h1 class="page-title">{{ isset($fasilitas) ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h1>
    <p class="page-subtitle">{{ isset($fasilitas) ? 'Ubah data fasilitas' : 'Tambah fasilitas baru' }}</p>

    <div class="form-admin-card">
        <form action="{{ isset($fasilitas) ? '/admin/fasilitas/' . $fasilitas->id_fasilitas : '/admin/fasilitas' }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($fasilitas)) @method('PUT') @endif

            <div class="row g-3 mb-3">
                <div class="col-8">
                    <label class="form-admin-label">
                        Nama Fasilitas
                    </label>
                    <input type="text" name="nama_fasilitas"
                           class="form-admin-control @error('nama_fasilitas') is-invalid @enderror"
                           placeholder="Contoh: WiFi, Kasur, AC..."
                           value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas ?? '') }}">
                    @error('nama_fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-4">
                    <label class="form-admin-label">
                        Tipe 
                    </label>
                    <select name="tipe" id="tipe-select"
                            class="form-admin-control @error('tipe') is-invalid @enderror">
                        @foreach(['Bersama', 'Pribadi'] as $t)
                            <option value="{{ $t }}"
                                    {{ old('tipe', $fasilitas->tipe ?? '') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <!-- <div class="col-6">
                    <label class="form-admin-label">Ikon (emoji atau teks singkat)</label>
                    <input type="text" name="ikon"
                           class="form-admin-control"
                           placeholder="Contoh: 📶 atau wifi"
                           value="{{ old('ikon', $fasilitas->ikon ?? '') }}">
                </div> -->
                <div class="col-6">
                    <label class="form-admin-label">Deskripsi (opsional)</label>
                    <input type="text" name="deskripsi"
                           class="form-admin-control"
                           placeholder="Keterangan singkat..."
                           value="{{ old('deskripsi', $fasilitas->deskripsi ?? '') }}">
                </div>
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label class="form-admin-label">Foto Fasilitas</label>
                <span style="font-size:0.75rem; color:#aaa; font-weight:400;">
                        (JPG/PNG maks. 2MB per foto)
                    </span>
                @if(isset($fasilitas) && $fasilitas->foto)
                    <img src="{{ asset('storage/' . $fasilitas->foto) }}"
                         style="width:80px; height:60px; object-fit:cover; border-radius:6px;
                                border:1px solid #ebebeb; display:block; margin-bottom:0.5rem;">
                @endif
                <div class="upload-admin-area">
                    <div id="placeholder-foto-fasilitas" class="upload-admin-text">
                        {{ isset($fasilitas) && $fasilitas->foto
                            ? 'Upload foto baru untuk mengganti'
                            : 'klik untuk upload foto' }}
                    </div>

                    <img id="preview-foto-fasilitas"
                        src=""
                        alt=""
                        style="max-width:100%; max-height:120px; border-radius:6px; display:none; margin-top:0.4rem;">

                    <input type="file"
                        name="foto"
                        accept="image/*"
                        onchange="previewFoto(this, 'preview-foto-fasilitas', 'placeholder-foto-fasilitas')">
                @error('foto_kamar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('foto_kamar.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Pilih Kamar (muncul hanya kalau tipe Pribadi) --}}
            <div class="mb-4" id="pilih-kamar-section"
                 @php $tipeLama = old('tipe', isset($fasilitas) ? $fasilitas->tipe : ''); @endphp
                style="{{ $tipeLama === 'Pribadi' ? '' : 'display:none;' }}">
                <label class="form-admin-label">Kamar yang memiliki fasilitas ini</label>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.3rem;">
                    @foreach($kamars as $kamar)
                    <label style="display:flex; align-items:center; gap:0.4rem;
                                  border:1.2px solid #ddd; border-radius:7px;
                                  padding:0.4rem 0.8rem; cursor:pointer; font-size:0.82rem;">
                        <input type="checkbox"
                               name="kamars[]"
                               value="{{ $kamar->id_kamar }}"
                               style="accent-color:#E8650A;"
                               {{ isset($fasilitas) && $fasilitas->kamars->contains($kamar->id_kamar) ? 'checked' : '' }}>
                        {{ $kamar->nomor_kamar }}
                        <span style="color:#aaa; font-size:0.75rem;">({{ $kamar->tipe_kamar }})</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <a href="/admin/data#fasilitas" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">
                    {{ isset($fasilitas) ? 'Simpan Perubahan' : 'Tambah Fasilitas' }}
                </button>
            </div>

        </form>
    </div>

@endsection