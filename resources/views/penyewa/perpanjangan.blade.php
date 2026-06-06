@extends('layouts.app')

@section('title', 'Perpanjangan Masa Sewa — Rumah Kos Faira')

@section('content')

<link rel="stylesheet" href="{{ asset('css/penyewa.css') }}">

<div class="container penyewa-page">
    <h1 class="penyewa-title">Perpanjangan Masa Sewa</h1>
    <p class="penyewa-subtitle">
        Kamar {{ $reservasi->kamar->nomor_kamar }} &nbsp;—&nbsp;
        Sewa sejak {{ \Carbon\Carbon::parse($reservasi->tanggal_masuk)->format('d M Y') }}
        &nbsp;—&nbsp; Batas bayar tanggal 10 tiap bulan
    </p>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert-penyewa alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-penyewa alert-error">{{ session('error') }}</div>
    @endif

    <!-- TAGIHAN SECTION -->
    @php
        $sudahLunas = $isBulanReservasi
                    || ($pembayaranBulanIni && $pembayaranBulanIni->status === 'Diterima');
        $menunggu   = $pembayaranBulanIni && $pembayaranBulanIni->status === 'Dikirim';
        $ditolak    = $pembayaranBulanIni && $pembayaranBulanIni->status === 'Ditolak';
        $cardClass  = $sudahLunas ? 'tagihan-card-lunas' : ($menunggu ? 'tagihan-card-menunggu' : 'tagihan-card-default');

    @endphp

    <div class="tagihan-card mt-4 {{ $cardClass }}">
        <div>
            <p class="tagihan-label">Tagihan</p>
            <p class="tagihan-bulan">
                Sewa Bulan {{ \Carbon\Carbon::parse($bulanIni)->isoFormat('MMMM Y') }}
            </p>
            @if($ditolak)
                <p style="font-size:0.78rem; color:#ef4444; margin-top:0.3rem;">
                    Pembayaran sebelumnya ditolak.
                    @if($pembayaranBulanIni->catatan_admin)
                        Alasan: {{ $pembayaranBulanIni->catatan_admin }}
                    @endif
                    Silakan kirim ulang.
                </p>
            @endif
        </div>

        @if($sudahLunas)
            {{-- Disabled, tidak bisa diklik --}}
            <button type="button" class="btn-orange" disabled
                    style="opacity:0.45; cursor:not-allowed;">
                Sudah Dibayar
            </button>

        @elseif($menunggu)
            <span class="badge-status badge-dikirim">Menunggu Konfirmasi</span>

        @else
        {{-- Belum bayar atau Ditolak --}}
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <p class="tagihan-harga" style="margin:0;">
                Rp {{ number_format($reservasi->kamar->harga, 0, ',', '.') }}
            </p>
           <button type="button"
                    class="btn-orange"
                    data-bulan="{{ $bulanIni }}"
                    data-bs-toggle="modal"
                    data-bs-target="#modalBayar">
                Bayar Sewa
            </button>
        </div>
        @endif
    </div>
    <!-- RIWAYAT PEMBAYARAN -->
    <div class="table-penyewa-wrap">
        <table class="table-penyewa">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tagihan Bulan</th>
                    <th>Tanggal Bayar</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal Konfirmasi Admin</th>
                    <th>Catatan Admin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatPembayaran as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->bulan_tagihan)->format('M Y') }}</td>
                    <td>
                        {{ $p->tanggal_bayar
                            ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y')
                            : '-' }}
                    </td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        {{ $p->tanggal_konfirmasi
                            ? \Carbon\Carbon::parse($p->tanggal_konfirmasi)->format('d M Y')
                            : '-' }}
                    </td>
                    <td>
                        {{$p->catatan_admin ?? '-'}}
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $p->bukti_transfer) }}"
                           target="_blank"
                           class="btn-detail">
                            Lihat Bukti
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="table-empty">
                        Belum ada riwayat pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL BAYAR-->
<div class="modal fade" id="modalBayar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: 1.5px solid #E8650A;">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0;">
                <h5 class="modal-title" style="font-size:1rem; font-weight:600;">
                    Upload Bukti Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/perpanjangan" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="bulan_tagihan" value="{{ $bulanIni }}">

                <div class="modal-body">

                    <!-- Bank -->
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

                    <!-- Upload Bukti -->
                    <div class="mb-3">
                        <label class="form-admin-label">
                            Bukti Transfer
                        </label>
                        <div class="upload-bukti-area" style="min-height:120px;">
                            <div id="placeholder-bukti-modal">
                                <div style="font-size:1.2rem;">📎</div>
                                <div class="upload-bukti-text">klik untuk upload bukti</div>
                            </div>
                            <img id="preview-bukti-modal"
                                src=""
                                alt=""
                                style="max-height:100px; display:none; border-radius:6px;">
                            <input type="file"
                                name="bukti_transfer"
                                accept="image/*,application/pdf"
                                onchange="previewFoto(this, 'preview-bukti-modal', 'placeholder-bukti-modal')">
                        </div>
                    </div>

                </div>

                <div class="modal-footer" style="border-top: 1px solid #f0f0f0;">
                    <button type="button"
                            class="btn-admin-cancel"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn-admin-submit">
                        Kirim Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <div id="pay-meta"
     data-sudah="{{ $sudahBayarBulanIni ? 'true' : 'false' }}"
     data-status="{{ $statusBayar }}"
     style="display:none">
</div>
@endsection
