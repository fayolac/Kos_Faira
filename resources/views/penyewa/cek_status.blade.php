@extends('layouts.app')

@section('title', 'Cek Status Reservasi — Rumah Kos Faira')

@section('content')

<link rel="stylesheet" href="{{ asset('css/penyewa.css') }}">

<div class="container penyewa-page">

    <h1 class="penyewa-title">Cek Status Reservasi</h1>
    <p class="penyewa-subtitle">
        @if($reservasi)
            Kamar {{ $reservasi->kamar->nomor_kamar }} —
            Tanggal Pengajuan {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d M Y') }}
        @else
            Belum ada pengajuan reservasi
        @endif
    </p>

    @if(!$reservasi)

        {{-- Belum pernah reservasi --}}
        <div class="status-card mt-4">
            <span class="status-icon">📋</span>
            <h2 class="status-card-title">Belum Ada Reservasi</h2>
            <p class="status-card-desc">
                Kamu belum pernah mengajukan reservasi kamar.
            </p>
            <a href="/kamar" class="btn-orange">Lihat Kamar</a>
        </div>

    @elseif(!$pembayaran)

        {{-- Reservasi ada tapi belum ada pembayaran --}}
        <div class="status-card mt-4 status-menunggu">
            <span class="status-icon">⏳</span>
            <h2 class="status-card-title">Menunggu Konfirmasi</h2>
            <p class="status-card-desc">
                Reservasi kamu sedang dalam proses verifikasi oleh pemilik kos.
            </p>
        </div>

    @elseif($pembayaran->status === 'Dikirim')

        {{-- Pembayaran dikirim, menunggu verifikasi --}}
        <div class="status-card mt-4 status-menunggu">
            <span class="status-icon">⏳</span>
            <h2 class="status-card-title">Menunggu Konfirmasi</h2>
            <p class="status-card-desc">
                Bukti pembayaran sudah diterima. Pemilik kos sedang memverifikasi.
            </p>
            <div class="status-card-info">
                <span class="status-card-info-label">Tanggal Bayar</span>
                <span class="status-card-info-value">
                    {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}
                </span>
            </div>
        </div>

    @elseif($pembayaran->status === 'Diterima')

        {{-- Reservasi berhasil --}}
        <div class="status-card mt-4 status-berhasil">
            <span class="status-icon">✅</span>
            <h2 class="status-card-title">Reservasi Berhasil</h2>
            <p class="status-card-desc">
                Pembayaran telah dikonfirmasi oleh pemilik kos
            </p>
            <div class="status-card-info">
                <span class="status-card-info-label">Tanggal bisa menempati</span>
                <span class="status-card-info-value">
                    {{ \Carbon\Carbon::parse($reservasi->tanggal_masuk)->format('d M Y') }}
                </span>
            </div>
            @if($pembayaran->catatan_admin)
                <div class="status-catatan-admin">
                    <strong>Catatan Admin:</strong> {{ $pembayaran->catatan_admin }}
                </div>
            @endif
            <a href="/kamar/{{ $reservasi->kamar->tipe_kamar }}" class="btn-orange">
                Lihat detail kamar saya
            </a>
        </div>

    @elseif($pembayaran->status === 'Ditolak')

        {{-- Reservasi ditolak --}}
        <div class="status-card mt-4 status-ditolak">
            <span class="status-icon">❌</span>
            <h2 class="status-card-title">Reservasi Ditolak</h2>
            <p class="status-card-desc">
                Maaf, pengajuan reservasi kamu tidak dapat diproses
            </p>
            @if($pembayaran->catatan_admin)
                <div class="status-catatan-admin">
                    <strong>Catatan Admin:</strong> {{ $pembayaran->catatan_admin }}
                </div>
            @endif
            <a href="/kamar" class="btn-orange">Reservasi Ulang</a>
        </div>

    @endif

</div>

@endsection