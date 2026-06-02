@extends('layouts.admin')

@section('title', 'Detail Pengaduan — Kos Faira')

@section('content')

    <h1 class="page-title">Detail Pengaduan</h1>

    <div class="pengaduan-detail-grid">

        {{-- ===== CARD KIRI: Informasi (Read Only) ===== --}}
        <div class="section-card">

            <p class="pengaduan-section-label">INFORMASI PENGADUAN</p>

            <div class="pengaduan-info-list">
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Tanggal pengaduan</span>
                    <span class="pengaduan-info-val">
                        {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y') }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nama penyewa</span>
                    <span class="pengaduan-info-val">
                        {{ $pengaduan->reservasi->penyewa->nama ?? '-' }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nomor kamar</span>
                    <span class="pengaduan-info-val">
                        {{ $pengaduan->reservasi->kamar->nomor_kamar ?? '-' }}
                        ({{ $pengaduan->reservasi->kamar->tipe_kamar ?? '' }})
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Judul pengaduan</span>
                    <span class="pengaduan-info-val" style="font-weight:500;">
                        {{ $pengaduan->judul }}
                    </span>
                </div>
                <div class="pengaduan-info-row" style="align-items:flex-start;">
                    <span class="pengaduan-info-key">Deskripsi keluhan</span>
                    <span class="pengaduan-info-val" style="line-height:1.7;">
                        {{ $pengaduan->keluhan }}
                    </span>
                </div>
            </div>

            {{-- Foto Bukti --}}
            @if($pengaduan->foto)
            <div class="mt-4">
                <p class="pengaduan-section-label">FOTO BUKTI</p>
                <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank">
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                         alt="Foto Pengaduan"
                         class="pengaduan-foto-bukti">
                </a>
                <p style="font-size:0.75rem; color:#aaa; margin-top:0.4rem;">
                    foto dari penyewa (klik untuk perbesar)
                </p>
            </div>
            @else
            <div class="mt-4">
                <p class="pengaduan-section-label">FOTO BUKTI</p>
                <div class="pengaduan-foto-placeholder">
                    Tidak ada foto
                </div>
            </div>
            @endif

        </div>

        {{-- ===== CARD KANAN: Update Status & Tanggapan ===== --}}
        <div class="section-card">

            <p class="pengaduan-section-label">UPDATE STATUS & TANGGAPAN</p>

            <form action="/admin/pengaduan/{{ $pengaduan->id_pengaduan }}"
                  method="POST">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-admin-label">
                        Status
                    </label>
                    <select name="status"
                            class="form-admin-control @error('status') is-invalid @enderror">
                        @foreach(['Diajukan', 'Diproses', 'Selesai'] as $s)
                        <option value="{{ $s }}"
                                {{ old('status', $pengaduan->status) == $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                        @endforeach
                    </select>
                    <small style="font-size:0.75rem; color:#aaa; margin-top:0.3rem; display:block;">
                        Diajukan → Diproses → Selesai
                    </small>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggapan Admin --}}
                <div class="mb-4">
                    <label class="form-admin-label">Tanggapan admin</label>
                    <textarea name="tanggapan_admin"
                              class="form-admin-control @error('tanggapan_admin') is-invalid @enderror"
                              rows="5"
                              placeholder="Tulis tanggapan atau tindakan yang akan dilakukan ...">{{ old('tanggapan_admin', $pengaduan->tanggapan_admin) }}</textarea>
                    @error('tanggapan_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div style="display:flex; gap:0.75rem; align-items:center;">
                    <a href="/admin/pengaduan" class="btn-admin-cancel">Batal</a>
                    <button type="submit" class="btn-admin-submit">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection