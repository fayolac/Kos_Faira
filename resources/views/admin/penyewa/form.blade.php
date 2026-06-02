@extends('layouts.admin')

@section('title', 'Detail Penyewa — Kos Faira')

@section('content')

    <h1 class="page-title">Detail Penyewa</h1>
    <div class="pengaduan-detail-grid">

        <!-- CARD KIRI: Data Diri  -->
        <div class="section-card">

            <p class="pengaduan-section-label">DATA DIRI</p>

            <div class="pengaduan-info-list">
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nama penyewa</span>
                    <span class="pengaduan-info-val">{{ $penyewa->nama }}</span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Email</span>
                    <span class="pengaduan-info-val">{{ $penyewa->email }}</span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nomor telepon</span>
                    <span class="pengaduan-info-val">{{ $penyewa->no_telp ?? '-' }}</span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Pekerjaan</span>
                    <span class="pengaduan-info-val">{{ $penyewa->pekerjaan ?? '-' }}</span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Agama</span>
                    <span class="pengaduan-info-val">{{ $penyewa->agama ?? '-' }}</span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Tanggal masuk</span>
                    <span class="pengaduan-info-val">
                        {{ $reservasiAktif
                            ? \Carbon\Carbon::parse($reservasiAktif->tanggal_masuk)->format('d M Y')
                            : '-' }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nomor kamar</span>
                    <span class="pengaduan-info-val">
                        {{ $reservasiAktif
                            ? $reservasiAktif->kamar->nomor_kamar . ' (' . $reservasiAktif->kamar->tipe_kamar . ')'
                            : '-' }}
                    </span>
                </div>
            </div>

            <!-- Foto KTP -->
            <div class="mt-4">
                <p class="pengaduan-section-label">FOTO KTP</p>
                @if($penyewa->foto_ktp)
                    <a href="{{ asset('storage/' . $penyewa->foto_ktp) }}" target="_blank">
                        <img src="{{ asset('storage/' . $penyewa->foto_ktp) }}"
                             alt="Foto KTP"
                             class="pengaduan-foto-bukti">
                    </a>
                @else
                    <div class="pengaduan-foto-placeholder">Foto KTP tidak tersedia</div>
                @endif
            </div>

        </div>

        <!-- CARD KANAN: Update Status -->
        <div class="section-card">

            <p class="pengaduan-section-label">UPDATE STATUS</p>

            <form action="/admin/penyewa/{{ $penyewa->id_penyewa }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-admin-label">
                        Status penyewa 
                    </label>
                    <select name="status"
                            class="form-admin-control @error('status') is-invalid @enderror">
                        @foreach(['Aktif', 'Nonaktif'] as $s)
                        <option value="{{ $s }}"
                                {{ old('status', $penyewa->status) == $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex; gap:0.75rem; align-items:center;">
                    <a href="/admin/penyewa" class="btn-admin-cancel">Batal</a>
                    <button type="submit" class="btn-admin-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection