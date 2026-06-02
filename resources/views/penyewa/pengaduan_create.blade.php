@extends('layouts.app')

@section('title', 'Tambah Pengaduan — Rumah Kos Faira')

@section('content')

<link rel="stylesheet" href="{{ asset('css/penyewa.css') }}">

<div class="container penyewa-page">

    <h1 class="penyewa-title">Tambah Pengaduan</h1>
    <p class="penyewa-subtitle">
        Kamar {{ $reservasi->kamar->nomor_kamar }} —
        Sampaikan keluhan atau gangguan yang kamu alami
    </p>

    <div class="form-penyewa-card mt-4">
        <form action="/pengaduan" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Judul --}}
            <div class="mb-3">
                <label class="form-admin-label">
                    Judul Pengaduan <span class="required-star">*</span>
                </label>
                <input type="text"
                       name="judul"
                       class="form-control @error('judul') is-invalid @enderror"
                       placeholder="Contoh: Lemari rusak, Atap bocor..."
                       value="{{ old('judul') }}">
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Keluhan --}}
            <div class="mb-3">
                <label class="form-admin-label">
                    Deskripsi Keluhan <span class="required-star">*</span>
                </label>
                <textarea name="keluhan"
                          class="form-control @error('keluhan') is-invalid @enderror"
                          rows="5"
                          placeholder="Jelaskan keluhan secara detail...">{{ old('keluhan') }}</textarea>
                @error('keluhan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-4">
                <label class="form-admin-label">
                    Foto Pendukung
                    <span style="font-size:0.75rem; color:#aaa; font-weight:400;">
                        (opsional — JPG/PNG maks. 2MB)
                    </span>
                </label>
                <div class="upload-bukti-area @error('foto') border-danger @enderror"
                     style="min-height: 140px;">
                    <div id="placeholder-foto-pengaduan">
                        <div class="upload-bukti-text">klik untuk upload foto</div>
                    </div>
                    <img id="preview-foto-pengaduan" src="" alt=""
                        style="max-height:120px; display:none; border-radius:6px;">
                    <input type="file"
                        name="foto"
                        accept="image/jpg,image/jpeg,image/png"
                        onchange="previewFoto(this, 'preview-foto-pengaduan', 'placeholder-foto-pengaduan')">
                </div>

                @error('foto')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="form-penyewa-actions">
                <a href="/pengaduan" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">Kirim Pengaduan</button>
            </div>

        </form>
    </div>

</div>

@endsection