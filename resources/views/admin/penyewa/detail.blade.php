@extends('layouts.admin')

@section('title', 'Detail Pembayaran — Kos Faira')

@section('content')

    <h1 class="page-title">Detail Pembayaran</h1>

    <div class="pengaduan-detail-grid">

        {{-- ===== CARD KIRI: Informasi Pembayaran (Read Only) ===== --}}
        <div class="section-card">

            <p class="pengaduan-section-label">INFORMASI PEMBAYARAN</p>

            <div class="pengaduan-info-list">
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nama penyewa</span>
                    <span class="pengaduan-info-val">
                        {{ $pembayaran->reservasi->penyewa->nama ?? '-' }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Nomor kamar</span>
                    <span class="pengaduan-info-val">
                        {{ $pembayaran->reservasi->kamar->nomor_kamar ?? '-' }}
                        @if($pembayaran->reservasi->kamar)
                            ({{ $pembayaran->reservasi->kamar->tipe_kamar }})
                        @endif
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Bulan tagihan</span>
                    <span class="pengaduan-info-val">
                        {{ \Carbon\Carbon::parse($pembayaran->bulan_tagihan)->format('M Y') }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Tipe pembayaran</span>
                    <span class="pengaduan-info-val">
                        <span class="{ strtolower($pembayaran->tipe_pembayaran) }}">
                            {{ $pembayaran->tipe_pembayaran }}
                        </span>
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Jumlah</span>
                    <span class="pengaduan-info-val">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Tanggal bayar</span>
                    <span class="pengaduan-info-val">
                        {{ $pembayaran->tanggal_bayar
                            ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y')
                            : '-' }}
                    </span>
                </div>
                <div class="pengaduan-info-row">
                    <span class="pengaduan-info-key">Tanggal konfirmasi</span>
                    <span class="pengaduan-info-val">
                        {{ $pembayaran->tanggal_konfirmasi
                            ? \Carbon\Carbon::parse($pembayaran->tanggal_konfirmasi)->format('d M Y')
                            : '-' }}
                    </span>
                </div>
            </div>

            {{-- Bukti Transfer --}}
            <div class="mt-4">
                <p class="pengaduan-section-label">BUKTI TRANSFER</p>
                @if($pembayaran->bukti_transfer)
                    <a href="{{ asset('storage/' . $pembayaran->bukti_transfer) }}"
                       target="_blank">
                        <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}"
                             alt="Bukti Transfer"
                             class="pengaduan-foto-bukti"
                             onerror="this.style.display='none';
                                      this.nextElementSibling.style.display='flex';">
                        <div class="pengaduan-foto-placeholder" style="display:none;">
                            📄 File tidak dapat ditampilkan — klik untuk buka
                        </div>
                    </a>
                @else
                    <div class="pengaduan-foto-placeholder">Bukti transfer tidak tersedia</div>
                @endif
            </div>
        </div>

        <!-- CARD KANAN: Verifikasi -->
        <div class="section-card">

            <p class="pengaduan-section-label">VERIFIKASI</p>

            @if($pembayaran->status === 'Diterima')

                <!-- Sudah diterima, tidak bisa diubah -->
                <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:1.2rem;">
                    <span class="badge-status badge-diterima">Diterima</span>
                    <span style="font-size:0.82rem; color:#888;">
                        Pembayaran telah dikonfirmasi
                    </span>
                </div>

                <div style="background:#f0fdf4; border:1.5px solid #10b981;
                            border-radius:10px; padding:1rem 1.2rem;
                            font-size:0.85rem; color:#065f46; margin-bottom:1.5rem;">
                    Pembayaran ini sudah berstatus <strong>Diterima</strong>
                    dan tidak dapat diubah kembali.
                </div>

                @if($pembayaran->catatan_admin)
                    <div class="mb-3">
                        <p class="form-admin-label">Catatan admin</p>
                        <p style="font-size:0.85rem; color:#555; margin:0;">
                            {{ $pembayaran->catatan_admin }}
                        </p>
                    </div>
                @endif

                <a href="/admin/verifikasi" class="btn-admin-cancel">
                    ← Kembali
                </a>

            @else

                <!-- Status Dikirim / Ditolak -->
                @if(session('error'))
                    <div class="alert-admin alert-error mb-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="/admin/pembayaran/{{ $pembayaran->id_pembayaran }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-admin-label">Status pembayaran</label>
                        <select name="status"
                                class="form-admin-control @error('status') is-invalid @enderror">
                            @foreach(['Diterima', 'Ditolak'] as $s)
                                <option value="{{ $s }}"
                                        {{ old('status', $pembayaran->status) == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catatan Admin -->
                    <div class="mb-4">
                        <label class="form-admin-label">
                            Catatan admin
                            <span style="font-size:0.75rem; color:#aaa; font-weight:400;">
                                (opsional)
                            </span>
                        </label>
                        <textarea name="catatan_admin"
                                class="form-admin-control @error('catatan_admin') is-invalid @enderror"
                                rows="4"
                                placeholder="Tambahkan catatan jika pembayaran ditolak atau ada informasi tambahan ...">{{ old('catatan_admin', $pembayaran->catatan_admin) }}</textarea>
                        @error('catatan_admin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tombol -->
                    <div style="display:flex; gap:0.75rem; align-items:center;">
                        <a href="/admin/verifikasi" class="btn-admin-cancel">Batal</a>
                        <button type="submit" class="btn-admin-submit">
                            Simpan Verifikasi
                        </button>
                    </div>

                </form>

            @endif

        </div>

    </div>
@endsection