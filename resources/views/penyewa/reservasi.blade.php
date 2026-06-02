@extends('layouts.app')

@section('title', 'Form Reservasi — Rumah Kos Faira')

@section('content')

<link rel="stylesheet" href="{{ asset('css/penyewa.css') }}">

<div class="container penyewa-page">

    <h1 class="penyewa-title">Form Reservasi</h1>
    <p class="penyewa-subtitle">Lengkapi form berikut untuk mengajukan reservasi.</p>

    <div class="form-penyewa-card mt-4">
        <form action="/reservasi" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">

            {{-- Baris 1: Tipe, Nomor, Tanggal --}}
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <label class="form-admin-label">Tipe Kamar</label>
                    <div class="form-penyewa-readonly">{{ $kamar->tipe_kamar }}</div>
                </div>
                <div class="col-4">
                    <label class="form-admin-label">Nomor Kamar</label>
                    <div class="form-penyewa-readonly">{{ $kamar->nomor_kamar }}</div>
                </div>
                <div class="col-4">
                    <label class="form-admin-label">
                        Tanggal Mulai Sewa <span class="required-star">*</span>
                    </label>
                    <input type="date"
                           name="tanggal_masuk"
                           class="form-control @error('tanggal_masuk') is-invalid @enderror"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('tanggal_masuk', request('tanggal_masuk')) }}">
                    @error('tanggal_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Via Pembayaran  -->
            <div class="mb-4">
                <label class="form-admin-label">Via Pembayaran</label>
                <p style="font-size:0.8rem; color:#aaa; margin-bottom:0.6rem;">
                    Lakukan transfer ke salah satu rekening berikut:
                </p>

                <div style="display:flex; flex-direction:column; gap:0.6rem;">
                    @foreach($banks as $bank)
                    <div style="background:#fffaf7; border:1.2px solid #f5c6a0;
                                border-radius:8px; padding:0.8rem 1rem;
                                font-size:0.85rem;">
                        <span style="font-weight:600; color:#1a1a1a;">
                            {{ $bank->nama_bank }}
                        </span>
                        <span style="color:#aaa; margin:0 0.4rem;">·</span>
                        <span style="font-family:monospace; color:#555;">
                            {{ $bank->nomor_rekening }}
                        </span>
                        <span style="color:#aaa; margin:0 0.4rem;">·</span>
                        <span style="color:#666;">a.n. {{ $bank->atas_nama }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Hidden field tetap kirim id_bank pertama agar validasi tidak error --}}
                @if($banks->count() > 0)
                    <input type="hidden" name="id_bank" value="{{ $banks->first()->id_bank }}">
                @endif
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="mb-4">
                <label class="form-admin-label">
                    Bukti Pembayaran <span class="required-star">*</span>
                </label>
                <div class="upload-bukti-area @error('bukti_transfer') border-danger @enderror">
                    <div id="placeholder-bukti-reservasi">
                        <div style="font-size:1.5rem;">📎</div>
                        <div class="upload-bukti-text">klik foto bukti pembayaran ke sini</div>
                        <div class="upload-bukti-text" style="font-size:0.75rem; margin-top:0.2rem;">
                            JPG / PNG / PDF - maks. 2MB
                        </div>
                    </div>
                    <img id="preview-bukti-reservasi"
                        src=""
                        alt=""
                        style="max-width:100%; max-height:160px; border-radius:6px; display:none;">
                    <input type="file"
                        name="bukti_transfer"
                        accept="image/*,application/pdf"
                        onchange="previewFoto(this, 'preview-bukti-reservasi', 'placeholder-bukti-reservasi')">
                </div>
                @error('bukti_transfer')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" style="padding: 1rem; background:#fff3ec;
                border-radius:8px; border:1px solid #f5c6a0;">
                <label style="display:flex; align-items:flex-start; gap:0.6rem;
                            cursor:pointer; font-size:0.85rem; color:#555;">
                    <input type="checkbox" name="setuju_peraturan" id="setuju-peraturan"
                        style="accent-color:#E8650A; margin-top:2px; flex-shrink:0;" required>
                        <span>
                        Saya telah membaca dan menyetujui
                        <a href="/peraturan" target="_blank"
                        style="color:#E8650A; font-weight:500;">
                            peraturan Rumah Kos Faira
                        </a>
                        dan bersedia mematuhinya selama masa sewa.
                    </span>
                </label>
            </div> 

            {{-- Tombol --}}
            <div class="form-penyewa-actions">
                <a href="/kamar/{{ $kamar->tipe_kamar }}" class="btn-admin-cancel">
                    Batal
                </a>
                <button type="submit" class="btn-admin-submit">
                    Ajukan Reservasi
                </button>
            </div>

        </form>
    </div>

</div>

@endsection