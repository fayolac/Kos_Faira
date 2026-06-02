@extends('layouts.admin')

@section('title', 'Data Kos — Kos Faira')

@section('content')

    <h1 class="page-title">Data Kos</h1>
    <p class="page-subtitle">Kelola kamar, fasilitas, peraturan, dan rekening bank</p>

    {{-- ===== TAB NAVIGATION ===== --}}
    <div class="section-card">

        <ul class="nav nav-tabs-admin" id="DataTab">
            <li class="nav-item">
                <a class="nav-link {{ request()->query('tab', 'kamar') === 'kamar' ? 'active' : '' }}"
                    href="{{ url('/admin/data') }}?tab=kamar">
                Kamar
            </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->query('tab') === 'fasilitas' ? 'active' : '' }}"
                    href="{{ url('/admin/data') }}?tab=fasilitas">
                Fasilitas
            </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->query('tab') === 'peraturan' ? 'active' : '' }}"
                    href="{{ url('/admin/data') }}?tab=peraturan">
                Peraturan
            </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->query('tab') === 'bank' ? 'active' : '' }}"
                    href="{{ url('/admin/data') }}?tab=bank">
                Bank
            </a>
            </li>
        </ul>

        <div class="tab-content pt-3">
                 <!-- TAB KAMAR -->
                <div class="tab-pane fade {{ request()->query('tab', 'kamar') === 'kamar' ? 'show active' : '' }}" id="tab-kamar">

                <div class="d-flex justify-content-end mb-3">
                    <a href="/admin/kamar/create" class="btn-tambah">Tambah Kamar</a>
                </div>

                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>No. Kamar</th>
                            <th>Tipe</th>
                            <th>Ukuran</th>
                            <th>Harga/Bulan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kamars as $k)
                        <tr>
                            <td style="font-weight:600;">{{ $k->nomor_kamar }}</td>
                            <td>{{ $k->tipe_kamar }}</td>
                            <td>{{ $k->ukuran_kamar ?? '-' }}</td>
                            <td>Rp {{ number_format($k->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($k->status) }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.4rem;">
                                    <a href="/admin/kamar/{{ $k->id_kamar }}/edit"
                                       class="btn-edit">Edit</a>
                                    <button type="button" class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-action="/admin/kamar/{{ $k->id_kamar }}"
                                            data-nama="Kamar {{ $k->nomor_kamar }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                                Belum ada data kamar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($kamars->hasPages())
                    <div class="pagination-wrap">{{ $kamars->links() }}</div>
                @endif

            </div>

             <!-- TAB FASILITAS -->
                <div class="tab-pane fade {{ request()->query('tab') === 'fasilitas' ? 'show active' : '' }}" id="tab-fasilitas">

                <div class="d-flex justify-content-end mb-3">
                    <a href="/admin/fasilitas/create" class="btn-tambah">Tambah Fasilitas</a>
                </div>

                {{-- Fasilitas Bersama --}}
                <p style="font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.6rem;">
                    Fasilitas Bersama
                </p>
                <table class="table-admin mb-4">
                    <thead>
                        <tr>
                            <th>Nama Fasilitas</th>
                            <th>Tipe</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitasBersama as $f)
                        <tr>
                            <td style="font-weight:500;">{{ $f->nama_fasilitas }}</td>
                            <td>Bersama</td>
                            <td>
                                @if($f->foto)
                                    <img src="{{ asset('storage/' . $f->foto) }}"
                                        class="table-foto" alt="{{ $f->nama_fasilitas }}">
                                @else
                                    <div class="table-foto-placeholder">—</div>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:0.4rem;">
                                    <a href="/admin/fasilitas/{{ $f->id_fasilitas }}/edit" class="btn-edit">Edit</a>
                                    <button type="button" class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-action="/admin/fasilitas/{{ $f->id_fasilitas }}"
                                            data-nama="{{ $f->nama_fasilitas }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                                Belum ada fasilitas bersama.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($fasilitasBersama->hasPages())
                    <div class="pagination-wrap">{{ $fasilitasBersama->links() }}</div>
                @endif

                <hr style="border-color:#f0e8e0; margin: 1.2rem 0;">

                {{-- Fasilitas Pribadi --}}
                <p style="font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.6rem;">
                    Fasilitas Pribadi (per kamar)
                </p>
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>Nama Fasilitas</th>
                            <th>Tipe</th>
                            <th>Kamar</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitasPribadi as $f)
                        <tr>
                            <td style="font-weight:500;">{{ $f->nama_fasilitas }}</td>
                            <td>Pribadi</td>
                            <td style="font-size:0.82rem; color:#666;">
                                @if($f->kamars->count() > 0)
                                    {{ $f->kamars->pluck('nomor_kamar')->join(', ') }}
                                @else
                                    <span style="color:#aaa;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($f->foto)
                                    <img src="{{ asset('storage/' . $f->foto) }}"
                                        class="table-foto" alt="{{ $f->nama_fasilitas }}">
                                @else
                                    <div class="table-foto-placeholder">—</div>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:0.4rem;">
                                    <a href="/admin/fasilitas/{{ $f->id_fasilitas }}/edit" class="btn-edit">Edit</a>
                                    <button type="button" class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-action="/admin/fasilitas/{{ $f->id_fasilitas }}"
                                            data-nama="{{ $f->nama_fasilitas }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                                Belum ada fasilitas pribadi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($fasilitasPribadi->hasPages())
                    <div class="pagination-wrap">{{ $fasilitasPribadi->links() }}</div>
                @endif

            </div>

            <!-- TAB Peraturan -->
                <div class="tab-pane fade {{ request()->query('tab') === 'peraturan' ? 'show active' : '' }}" id="tab-peraturan">

                <div class="d-flex justify-content-end mb-3">
                    <a href="/admin/peraturan/create" class="btn-tambah">Tambah Peraturan</a>
                </div>

                <table class="table-admin">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th style="width:200px;">Judul Peraturan</th>
                            <th>Isi Peraturan</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peraturans as $i => $p)
                        <tr>
                            <td>{{ $peraturans->firstItem() + $loop->index }}</td>
                            <td style="font-weight:500;">{{ $p->judul }}</td>
                            <td style="font-size:0.83rem; color:#555; line-height:1.6;">
                                {{ Str::limit($p->isi, 120) }}
                            </td>
                            <td>
                                <div style="display:flex; gap:0.4rem;">
                                    <a href="/admin/peraturan/{{ $p->id_peraturan }}/edit"
                                       class="btn-edit">Edit</a>
                                    <button type="button" class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-action="/admin/peraturan/{{ $p->id_peraturan }}"
                                            data-nama="{{ $p->judul }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                                Belum ada peraturan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($peraturans->hasPages())
                    <div class="pagination-wrap">{{ $peraturans->links() }}</div>
                @endif

            </div>
                 
            <!-- TAB BANK -->
                <div class="tab-pane fade {{ request()->query('tab') === 'bank' ? 'show active' : '' }}" id="tab-bank">

                <div class="d-flex justify-content-end mb-3">
                    <a href="/admin/bank/create" class="btn-tambah">Tambah Rekening</a>
                </div>

                <table class="table-admin">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th>Nama Bank</th>
                            <th>Atas Nama</th>
                            <th>Nomor Rekening</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $i => $b)
                        <tr>
                            <td>{{ $banks->firstItem() + $loop->index }}</td>
                            <td style="font-weight:500;">{{ $b->nama_bank }}</td>
                            <td>{{ $b->atas_nama }}</td>
                            <td style="letter-spacing:0.05em;">
                                {{ $b->nomor_rekening }}
                            </td>
                            <td>
                                <div style="display:flex; gap:0.4rem;">
                                    <a href="/admin/bank/{{ $b->id_bank }}/edit"
                                       class="btn-edit">Edit</a>
                                    <button type="button" class="btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-action="/admin/bank/{{ $b->id_bank }}"
                                            data-nama="{{ $b->nama_bank }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                                Belum ada rekening bank.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($banks->hasPages())
                    <div class="pagination-wrap">{{ $banks->links() }}</div>
                @endif

            </div>
        </div>
    </div>

{{-- ===== MODAL HAPUS (universal) ===== --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:12px; border:1px solid #ebebeb;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;">
                <h5 class="modal-title" style="font-size:0.95rem; font-weight:600;">
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="font-size:0.85rem; color:#555;">
                <p style="margin-bottom:0.4rem;">Yakin ingin menghapus:</p>
                <p id="modal-hapus-nama" style="font-weight:600; color:#1a1a1a;">—</p>
                <p style="color:#aaa; font-size:0.78rem; margin-bottom:0;">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                <button type="button" class="btn-admin-cancel" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="form-hapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-admin-submit"
                            style="background-color:#ef4444;">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', function (e) {
        const btn    = e.relatedTarget;
        const action = btn.dataset.action;
        const nama   = btn.dataset.nama;
        document.getElementById('modal-hapus-nama').textContent = nama;
        document.getElementById('form-hapus').action = action;
    });

    // Isi modal hapus
    document.getElementById('modalHapus').addEventListener('show.bs.modal', function (e) {
        const btn    = e.relatedTarget;
        const action = btn.dataset.action;
        const nama   = btn.dataset.nama;
        document.getElementById('modal-hapus-nama').textContent = nama;
        document.getElementById('form-hapus').action = action;
    });
</script>
@endpush