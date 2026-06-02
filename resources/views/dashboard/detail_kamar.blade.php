@extends('layouts.app')

@section('title', 'Kamar ' . $tipe . ' — Rumah Kos Faira')

@section('content')

<div class="container section-public">

    <h1 class="page-public-title">Kamar {{ $tipe }}</h1>

    <div class="detail-kamar-grid">

        {{-- ===== KIRI: Foto & Fasilitas ===== --}}
        <div class="detail-left">

            {{-- Foto Kamar — dinamis berdasarkan kamar dipilih --}}
            <div class="detail-foto-list" id="foto-kamar-container">
                @forelse($kamar->fotos->take(5) as $foto)
                    <a href="{{ asset('storage/' . $foto->foto) }}"
                       target="_blank"
                       class="detail-foto-link">
                        <img src="{{ asset('storage/' . $foto->foto) }}"
                             alt="Foto Kamar {{ $tipe }}"
                             class="detail-foto-item">
                    </a>
                @empty
                    <div class="detail-foto-item detail-foto-empty">
                        Foto belum tersedia
                    </div>
                @endforelse
            </div>

            {{-- Fasilitas Kamar (Pribadi) --}}
            @if($kamar->fasilitass->where('tipe', 'Pribadi')->count() > 0)
                <h3 class="detail-section-title">Fasilitas Kamar</h3>
                <div class="fasilitas-grid-small">
                    @foreach($kamar->fasilitass->where('tipe', 'Pribadi') as $f)
                    <div class="fasilitas-card-small">
                        @if($f->foto)
                            <img src="{{ asset('storage/' . $f->foto) }}"
                                 alt="{{ $f->nama_fasilitas }}"
                                 class="fasilitas-img-small">
                        @else
                            <div class="fasilitas-img-small-placeholder">
                                {{ $f->ikon ?? '📦' }}
                            </div>
                        @endif
                        <p class="fasilitas-name-small">{{ $f->nama_fasilitas }}</p>
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Fasilitas Umum (Bersama) --}}
            @if($fasilitasBersama->count() > 0)
                <h3 class="detail-section-title">Fasilitas Umum</h3>
                <div class="fasilitas-list">
                    @foreach($fasilitasBersama as $f)
                        <span class="fasilitas-tag">
                            {{ $f->ikon ?? '✓' }} {{ $f->nama_fasilitas }}
                        </span>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- ===== KANAN: Info & Reservasi ===== --}}
        <div class="detail-right">
            <div class="detail-info-card">

                <div class="detail-harga-wrap">
                    <p class="detail-harga-label">Harga per bulan</p>
                    <p class="detail-harga">
                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                    </p>
                </div>

                <hr class="detail-divider">

                <p class="detail-info-heading">Detail Kamar</p>
                <div class="detail-info-row">
                    <span>Tipe</span>
                    <span>{{ $kamar->tipe_kamar }}</span>
                </div>
                <div class="detail-info-row">
                    <span>Ukuran</span>
                    <span>{{ $kamar->ukuran_kamar ?? '-' }}</span>
                </div>
                <div class="detail-info-row">
                    <span>Status</span>
                    <span class="badge-status badge-tersedia">Tersedia</span>
                </div>

                <hr class="detail-divider">

                <p class="detail-info-heading">Pilih nomor kamar</p>
                @php
                    $idKamarTersedia = $kamarTersedia->pluck('id_kamar')->toArray();
                    $adaYangTersedia = $kamarTersedia->count() > 0;
                @endphp

                @if($semuaKamar->count() > 0)
                    <div class="nomor-kamar-grid">
                        @foreach($semuaKamar as $k)
                        @php $tersedia = in_array($k->id_kamar, $idKamarTersedia); @endphp
                        <button type="button"
                                class="btn-nomor-kamar {{ !$tersedia ? 'disabled-kamar' : '' }}"
                                data-id="{{ $k->id_kamar }}"
                                @if(!$tersedia) disabled @endif
                                @if($tersedia) onclick="pilihKamar(this)" @endif
                                title="{{ !$tersedia ? 'Kamar ini sudah terisi' : '' }}">
                            {{ $k->nomor_kamar }}
                            @if(!$tersedia)
                                <br><span style="font-size:0.65rem;">Terisi</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                @endif

                @if(!$adaYangTersedia)
                    <div class="kamar-full-notice">
                        Semua kamar {{ $tipe }} sedang terisi penuh.
                    </div>
                @endif

                @if($kamarTersedia->count() > 0)
                <div class="mb-3 mt-3">
                    <label style="font-size:0.82rem; font-weight:500; color:#555; display:block; margin-bottom:0.3rem;">
                        Tanggal Masuk
                    </label>
                    <input type="date"
                           id="input-tanggal-masuk"
                           class="form-control"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           style="font-size:0.88rem;">
                </div>
                @endif

                @if($adaYangTersedia)
                <a href="#"
                   id="btn-ajukan-reservasi"
                   class="btn-orange-full d-block text-center mt-3"
                   style="opacity:0.5; pointer-events:none; text-decoration:none; padding:0.65rem;">
                    Ajukan Reservasi
                </a>
                @else
                <button type="button" class="btn-orange-full mt-3" disabled
                        style="opacity:0.5; cursor:not-allowed; width:100%;">
                    Kamar Penuh
                </button>
                @endif

                <p class="detail-nb">
                    NB : Reservasi memerlukan akun terdaftar
                </p>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
{{-- Data foto, aman dari linter karena type bukan javascript --}}
<script type="application/json" id="foto-data">
    {
    @foreach($semuaKamar as $k)
        "{{ $k->id_kamar }}": [
            @php $fotos = $k->fotos->take(5); @endphp
            @foreach($fotos as $foto)
                "{{ asset('storage/' . $foto->foto) }}"{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]{{ !$loop->last ? ',' : '' }}
    @endforeach
    }
</script>

@push('scripts')
<script>
    const fotoPerKamar = JSON.parse(document.getElementById('foto-data').textContent);

    function pilihKamar(el) {
        document.querySelectorAll('.btn-nomor-kamar')
                .forEach(b => b.classList.remove('selected'));
        el.classList.add('selected');

        const idKamar = el.dataset.id;
        updateFotoKamar(idKamar);
        updateBtnReservasi(idKamar);
    }

    function updateFotoKamar(idKamar) {
        const container = document.getElementById('foto-kamar-container');
        const fotos     = fotoPerKamar[idKamar] || [];

        container.innerHTML = '';

        if (fotos.length === 0) {
            container.innerHTML = `
                <div class="detail-foto-item detail-foto-empty">
                    Foto belum tersedia
                </div>`;
            return;
        }

        fotos.forEach(url => {
            const a     = document.createElement('a');
            a.href      = url;
            a.target    = '_blank';
            a.className = 'detail-foto-link';

            const img     = document.createElement('img');
            img.src       = url;
            img.alt       = 'Foto Kamar';
            img.className = 'detail-foto-item';

            a.appendChild(img);
            container.appendChild(a);
        });
    }

    function updateBtnReservasi(idKamar) {
        const btn          = document.getElementById('btn-ajukan-reservasi');
        const inputTanggal = document.getElementById('input-tanggal-masuk');

        if (!btn) return;
        btn.style.opacity       = '1';
        btn.style.pointerEvents = 'auto';

        const tanggal = inputTanggal ? inputTanggal.value : '';
        let url       = '/reservasi?id_kamar=' + idKamar;
        if (tanggal) url += '&tanggal_masuk=' + tanggal;
        btn.href = url;
    }

    const inputTanggal = document.getElementById('input-tanggal-masuk');
    if (inputTanggal) {
        inputTanggal.addEventListener('change', function () {
            const selectedBtn = document.querySelector('.btn-nomor-kamar.selected');
            if (selectedBtn) updateBtnReservasi(selectedBtn.dataset.id);
        });
    }
</script>
@endpush