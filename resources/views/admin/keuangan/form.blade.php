@extends('layouts.admin')

@section('title', isset($pengeluaran) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran')

@section('content')

    {{-- Page Header --}}
    <h1 class="page-title">
        {{ isset($pengeluaran) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}
    </h1>
    <p class="page-subtitle">
        {{ isset($pengeluaran) ? 'Ubah data pengeluaran operasional' : 'Catat pengeluaran operasional baru' }}
    </p>

    <div class="form-admin-card">

        <form action="{{ isset($pengeluaran)
                          ? '/admin/pengeluaran/' . $pengeluaran->id_pengeluaran
                          : '/admin/pengeluaran' }}"
              method="POST">
            @csrf
            @if(isset($pengeluaran))
                @method('PUT')
            @endif

            <div class="row g-3 mb-3">

                <!-- Kategori  -->
                <div class="col-6">
                    <label class="form-admin-label">
                        Kategori
                    </label>
                    <select name="kategori"
                            class="form-admin-control @error('kategori') is-invalid @enderror">
                        <option value="" disabled
                                {{ !isset($pengeluaran) ? 'selected' : '' }}>
                            Pilih kategori ...
                        </option>
                        @foreach(['Wifi','Gas','Air','Listrik','Sampah','Pemeliharaan','Lainnya'] as $kat)
                        <option value="{{ $kat }}"
                                {{ old('kategori', $pengeluaran->kategori ?? '') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="col-6">
                    <label class="form-admin-label">
                        Tanggal
                    </label>
                    <input type="date"
                           name="tanggal"
                           class="form-admin-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', $pengeluaran->tanggal ?? '') }}">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row g-3 mb-4">

                {{-- Jumlah --}}
                <div class="col-6">
                    <label class="form-admin-label">
                        Jumlah (Rp)
                    </label>
                    <input type="number"
                           name="jumlah"
                           class="form-admin-control @error('jumlah') is-invalid @enderror"
                           placeholder="0"
                           min="1"
                           value="{{ old('jumlah', $pengeluaran->jumlah ?? '') }}">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="col-6">
                    <label class="form-admin-label">Deskripsi (opsional)</label>
                    <input type="text"
                           name="keterangan"
                           class="form-admin-control @error('keterangan') is-invalid @enderror"
                           placeholder="keterangan singkat ..."
                           value="{{ old('keterangan', $pengeluaran->keterangan ?? '') }}">
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:0.75rem; align-items:center;">
                <a href="/admin/keuangan" class="btn-admin-cancel">Batal</a>
                <button type="submit" class="btn-admin-submit">
                    {{ isset($pengeluaran) ? 'Simpan Perubahan' : 'Simpan Pengeluaran' }}
                </button>
            </div>

        </form>
    </div>

@endsection