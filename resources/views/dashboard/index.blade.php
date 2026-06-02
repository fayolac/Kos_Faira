@extends('layouts.app')

@section('title', 'Beranda — Rumah Kos Faira')

@section('content')

<!-- HERO Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-text">
                <h1 class="hero-title">
                    Rumah Kos Faira<br>
                </h1>
                <p class="hero-desc">
                    Hunian nyaman, bersih, dan aman untuk muslimah
                    di kawasan strategis Lowokwaru, Malang.
                    Dekat kampus dan pusat kota.
                </p>
                <div class="hero-buttons">
                    <a href="/kamar" class="btn-orange">Lihat Kamar</a>
                    <a href="https://wa.me/628127394810" 
                       target="-_blank" class="btn-orange-outline">Hubungi Kami</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('img/Kos Faira.jpg') }}"
                     alt="Rumah Kos Faira"
                     onerror="this.style.display='none';
                              this.nextElementSibling.style.display='flex';">
                <div class="img-placeholder">Foto Kos Faira</div>
            </div>
        </div>
    </div>
</section>

<!-- Pilihan Kamar -->
<section class="section-public">
    <div class="container">
        <h2 class="section-title text-center">Pilihan Kamar</h2>

        <div class="kamar-preview-grid">

            <!-- Kamar Basic -->
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
                        Rp {{ number_format($hargaBasic, 0, ',', '.') }}
                        <span>/bulan</span> 
                    </p>
                    <a href="/kamar/Basic" class="btn-orange-outline w-100 text-center d-block">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endif

            <!-- Kamar Plus -->
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
                        Rp {{ number_format($hargaPlus, 0, ',', '.') }}
                        <span>/bulan</span>
                    </p>
                    <a href="/kamar/Plus" class="btn-orange-outline w-100 text-center d-block">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

<!-- FASILITAS BERSAMA -->
<section class="section-public section-bg-light">
    <div class="container">
        <h2 class="section-title text-center">Fasilitas Bersama</h2>
        <p class="section-subtitle text-center">
            Fasilitas umum yang tersedia untuk semua penghuni
        </p>

        <div class="fasilitas-grid">
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
            <p class="text-muted text-center w-100">Belum ada data fasilitas.</p>
            @endforelse
        </div>
        @if($fasilitasBersama->count() > 0)
            <p class="text-center mt-2" style="color: #111; font-size: 1rem;">and more...</p>
        @endif
    </div>
</section>

<section class="section-public" id="lokasi">
    <div class="container">
        <div class="lokasi-inner">

            <!-- CTA -->
            <div class="lokasi-cta">
                <h2>Anda Tertarik?<br>Yuk gabung sekarang</h2>
                <a href="https://wa.me/6285855219083" class="btn-cta-white mt-3 d-inline-block">
                    Hubungi Kami
                </a>
                <div class="lokasi-cta-info">
                    <p><strong>Bapak Mulyo S</strong></p>
                    <p>+62 812-7839-4810</p>
                </div>
            </div>

            <!-- Maps -->
            <div class="lokasi-map">
                <h2 class="section-title">Lokasi Kami</h2>
                <div class="maps-wrapper">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.4750656136125!2d112.6028468!3d-7.949759299999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e788300339ab587%3A0x221684cee82a9f28!2sKos%20Faira%20(Kos%20Putri%20Muslimah)!5e0!3m2!1sid!2sid!4v1777647009241!5m2!1sid!2sid" 
                        width="600" height="450" style="border:0;" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <p class="lokasi-address">
                    Jl. Joyosuko Metro IIA No.14, Merjosari,<br>
                    Kecamatan Lowokwaru, Kota Malang
                </p>
            </div>

        </div>
    </div>
</section>

@endsection