@extends('layouts.app')

@section('title', 'Kamar & Fasilitas — Rumah Kos Faira')

@section('content')

<div class="container section-public">

    {{-- Header --}}
    <h1 class="page-public-title">Kamar & Fasilitas</h1>
    <p class="page-public-subtitle">
        Pilih tipe kamar yang sesuai dan lihat fasilitas bersama yang tersedia di Rumah Kos Faira
    </p>

    {{-- ===== PILIHAN KAMAR ===== --}}
    <h2 class="section-title mt-4">Pilihan Kamar</h2>

    <div class="kamar-preview-grid">

        @if($kamarBasic)
        <div class="kamar-preview-card">
            @if($kamarBasic->fotoUtama)
                <img src="{{ asset('storage/' . $kamarBasic->fotoUtama->foto) }}"
                     alt="Kamar Basic"
                     class="kamar-preview-img">
            @else
                <div class="kamar-preview-img-placeholder">Foto Kamar</div>
            @endif
            <div class="kamar-preview-body">
                <h3 class="kamar-preview-name">Kamar Basic</h3>
                <p class="kamar-preview-size">Ukuran {{ $kamarBasic->ukuran_kamar }}</p>
                <p class="kamar-preview-price">
                    Rp {{ number_format($kamarBasic->harga, 0, ',', '.') }}
                    <span>/bulan</span>
                </p>
                <a href="/kamar/Basic" class="btn-orange-outline w-100 text-center d-block">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endif

        @if($kamarPlus)
        <div class="kamar-preview-card">
            @if($kamarPlus->fotoUtama)
                <img src="{{ asset('storage/' . $kamarPlus->fotoUtama->foto) }}"
                     alt="Kamar Plus"
                     class="kamar-preview-img">
            @else
                <div class="kamar-preview-img-placeholder">Foto Kamar</div>
            @endif
            <div class="kamar-preview-body">
                <h3 class="kamar-preview-name">Kamar Plus</h3>
                <p class="kamar-preview-size">Ukuran {{ $kamarPlus->ukuran_kamar }}</p>
                <p class="kamar-preview-price">
                    Rp {{ number_format($kamarPlus->harga, 0, ',', '.') }}
                    <span>/bulan</span>
                </p>
                <a href="/kamar/Plus" class="btn-orange-outline w-100 text-center d-block">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endif

    </div>

    {{-- ===== DENAH ===== --}}
    <h2 class="section-title mt-5">Denah Rumah Kos Faira</h2>

    <div class="denah-wrapper">
        <div class="denah-image">
            <img src="{{ asset('img/denah.jpg') }}"
                 alt="Denah Kos Faira"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">        </div>
        
            <div class="denah-catatan">
            <p>
                <strong>Catatan:</strong> Denah ini merupakan gambaran umum tata letak kos,
                bukan ukuran skala sebenarnya. Posisi dan detail aktual dapat berbeda.
                Hubungi pemilik untuk informasi lebih lanjut.
            </p>
        </div>
    </div>

    {{-- ===== FASILITAS BERSAMA ===== --}}
    <h2 class="section-title mt-5">Fasilitas Bersama</h2>
    <p class="page-public-subtitle">
        Fasilitas umum yang tersedia untuk seluruh penghuni Rumah Kos Faira
    </p>

    <div class="fasilitas-grid mt-3">
        @forelse($fasilitasBersama as $fasilitas)
        <div class="fasilitas-card">
            @if($fasilitas->foto)
                <img src="{{ asset('storage/' . $fasilitas->foto) }}"
                     alt="{{ $fasilitas->nama_fasilitas }}"
                     class="fasilitas-img">
            @endif
            <p class="fasilitas-name">{{ $fasilitas->nama_fasilitas }}</p>
            @if($fasilitas->deskripsi)
                <p class="fasilitas-desc">{{ $fasilitas->deskripsi }}</p>
            @endif
        </div>
        @empty
            <p class="text-muted">Belum ada data fasilitas.</p>
        @endforelse
    </div>

</div>

@endsection