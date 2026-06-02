@extends('layouts.admin')

@section('title', isset($kamar) ? 'Edit Kamar' : 'Tambah Kamar')

@section('content')

<h1 class="page-title">{{ isset($kamar) ? 'Edit Kamar' : 'Tambah Kamar' }}</h1>
    <p class="page-subtitle">
        {{ isset($kamar) ? 'Ubah data kamar' : 'Tambah data kamar baru' }}
    </p>

    <div class="form-admin-card">
        <form action="{{ isset($kamar) ? '/admin/kamar/' . $kamar->id_kamar : '/admin/kamar' }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @if(isset($kamar)) @method('PUT') @endif

            <div class="row g-3 mb-3">
                <div class="col-4">
                    <label class="form-admin-label">
                        Nomor Kamar
                    </label>
                    <input type="text" name="nomor_kamar"
                           class="form-admin-control @error('nomor_kamar') is-invalid @enderror"
                           placeholder="Contoh: A"
                           value="{{ old('nomor_kamar', $kamar->nomor_kamar ?? '') }}">
                    @error('nomor_kamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-4">
                    <label class="form-admin-label">
                        Tipe Kamar
                    </label>
                    <select name="tipe_kamar"
                            class="form-admin-control @error('tipe_kamar') is-invalid @enderror">
                        <option value="" disabled {{ !isset($kamar) ? 'selected' : '' }}>Pilih tipe...</option>
                        @foreach(['Basic', 'Plus'] as $t)
                            <option value="{{ $t }}"
                                    {{ old('tipe_kamar', $kamar->tipe_kamar ?? '') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipe_kamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-4">
                    <label class="form-admin-label">
                        Status 
                    </label>
                    <select name="status"
                            class="form-admin-control @error('status') is-invalid @enderror">
                        @foreach(['Tersedia', 'Terisi', 'Nonaktif'] as $s)
                            <option value="{{ $s }}"
                                    {{ old('status', $kamar->status ?? 'Tersedia') == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-admin-label">Harga per Bulan (Rp)</label>
                    <div class="form-penyewa-readonly" id="info-harga">
                        Akan ditentukan otomatis dari tipe kamar
                    </div>
                    <small style="font-size:0.75rem; color:#aaa;">
                        Basic = Rp 520.000 · Plus = Rp 570.000
                    </small>
                </div>
                <div class="col-6">
                    <label class="form-admin-label">Ukuran Kamar</label>
                    <input type="text" name="ukuran_kamar"
                           class="form-admin-control @error('ukuran_kamar') is-invalid @enderror"
                           placeholder="Contoh: 3 × 3 m"
                           value="{{ old('ukuran_kamar', $kamar->ukuran_kamar ?? '') }}">
                    @error('ukuran_kamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Foto Kamar --}}
            <div class="mb-3">
                <label class="form-admin-label">
                    Foto Kamar
                    <span style="font-size:0.75rem; color:#aaa; font-weight:400;">
                        (bisa lebih dari 1 — JPG/PNG maks. 2MB per foto)
                    </span>
                </label>

                @if(isset($kamar) && $kamar->fotos->count() > 0)
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.6rem;">
                    @foreach($kamar->fotos as $foto)
                    <div style="position:relative;">
                        <img src="{{ asset('storage/' . $foto->foto) }}"
                             style="width:80px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ebebeb;">
                    </div>
                    @endforeach
                </div>
                <p style="font-size:0.75rem; color:#aaa; margin-bottom:0.4rem;">
                    Foto lama di atas. Upload foto baru akan ditambahkan.
                </p>
                @endif

                <div class="upload-admin-area">
                    <div id="placeholder-foto-kamar" class="upload-admin-text">
                        klik untuk upload foto kamar (bisa lebih dari 1)
                    </div>

                    <div id="preview-foto-kamar-wrap"
                        style="display:none; gap:0.4rem; flex-wrap:wrap; margin-top:0.4rem;">
                    </div>

                    <input type="file"
                        name="foto_kamar[]"
                        multiple
                        accept="image/*"
                        onchange="previewFotoMultiple(this, 'preview-foto-kamar-wrap', 'placeholder-foto-kamar')">
                </div>
                @error('foto_kamar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('foto_kamar.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Fasilitas --}}
            <div class="mb-4">
                <label class="form-admin-label">Fasilitas Pribadi Kamar</label>
                <p style="font-size:0.75rem; color:#aaa; margin-bottom:0.5rem;">
                    Centang fasilitas pribadi yang tersedia di kamar ini.
                    Fasilitas bersama (WiFi, dll) otomatis dimiliki semua kamar.
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.3rem;">
                    @foreach($fasilitass->where('tipe', 'Pribadi') as $f)
                    <label style="display:flex; align-items:center; gap:0.4rem;
                                border:1.2px solid #ddd; border-radius:7px;
                                padding:0.4rem 0.8rem; cursor:pointer;
                                font-size:0.82rem; transition:all 0.15s;">
                        <input type="checkbox"
                            name="fasilitas[]"
                            value="{{ $f->id_fasilitas }}"
                            style="accent-color: #E8650A;"
                            {{ isset($kamar) && $kamar->fasilitass->contains($f->id_fasilitas) ? 'checked' : '' }}>
                        {{ $f->nama_fasilitas }}
                    </label>
                    @endforeach

                    @if($fasilitass->where('tipe', 'Pribadi')->count() === 0)
                        <p style="font-size:0.82rem; color:#aaa;">
                            Belum ada fasilitas pribadi. Tambahkan dulu di menu Fasilitas.
                        </p>
                    @endif
                </div>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <a href="/admin/data#kamar" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">
                    {{ isset($kamar) ? 'Simpan Perubahan' : 'Tambah Kamar' }}
                </button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
<script>
    const tipeSelect  = document.getElementById('tipe-select') 
                        || document.querySelector('[name="tipe_kamar"]');
    const infoHarga   = document.getElementById('info-harga');
    const hargaMap    = { 'Basic': 'Rp 520.000', 'Plus': 'Rp 570.000' };

    if (tipeSelect && infoHarga) {
        // Set awal
        if (tipeSelect.value) {
            infoHarga.textContent = hargaMap[tipeSelect.value] || '-';
        }

        tipeSelect.addEventListener('change', function() {
            infoHarga.textContent = hargaMap[this.value] || 'Pilih tipe dulu';
        });
    }
</script>
@endpush