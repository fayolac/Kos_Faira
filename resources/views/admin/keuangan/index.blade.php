@extends('layouts.admin')

@section('title', 'Data Keuangan — Kos Faira')

@section('content')

    <!-- Page Header -->
    <h1 class="page-title">Data Keuangan</h1>
    <p class="page-subtitle">Rekap pemasukan dan pengeluaran operasional kos</p>

    <!-- Filter -->
     <form method="GET" action="/admin/keuangan" class="mb-3">
        <div class="d-flex align-items-center gap-2">
            <label class="fw-semibold mb-0">Filter Tahun:</label>
            <select name="tahun" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $t == $tahunDipilih ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <!--CARD RINGKASAN -->
    <div class="row g-3 mb-4">

        <div class="col-4">
            <div class="rekap-card rekap-pemasukan">
                <p class="rekap-card-label">Total Pemasukan</p>
                <p class="rekap-card-value">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </p>
                <p class="rekap-card-meta">
                    {{ $pemasukans->total() }} transaksi dikonfirmasi
                </p>
            </div>
        </div>

        <div class="col-4">
            <div class="rekap-card rekap-pengeluaran">
                <p class="rekap-card-label">Total Pengeluaran</p>
                <p class="rekap-card-value">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </p>
                <p class="rekap-card-meta">
                    {{ $pengeluarans->total() }} catatan pengeluaran
                </p>
            </div>
        </div>

        <div class="col-4">
            <div class="rekap-card {{ $saldoBersih >= 0 ? 'rekap-saldo-positif' : 'rekap-saldo-negatif' }}">
                <p class="rekap-card-label">Saldo Bersih</p>
                <p class="rekap-card-value">
                    {{ $saldoBersih < 0 ? '- ' : '' }}Rp {{ number_format(abs($saldoBersih), 0, ',', '.') }}
                </p>
                <p class="rekap-card-meta">Pemasukan &minus; Pengeluaran</p>
            </div>

        </div>

    </div>

    <!-- Tabel Pemasukan -->
    <!-- <div class="section-card mb-4"> -->
    <div class="mb-4">
        <div class="section-card-header">
            <div>
                <p class="section-card-title">Rekap Pemasukan</p>
                <p class="section-card-subtitle">
                    Pembayaran sewa yang sudah dikonfirmasi
                </p>
            </div>
        </div>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Masuk</th>
                    <th>Tipe</th>
                    <th>Bulan Tagihan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        {{ $p->tanggal_konfirmasi
                            ? \Carbon\Carbon::parse($p->tanggal_konfirmasi)->format('d M Y')
                            : '-' }}
                    </td>
                    <td>
                        <span class="{{ strtolower($p->tipe_pembayaran) }}">
                            {{ $p->tipe_pembayaran }}
                        </span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->bulan_tagihan)->format('M Y') }}
                    </td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                        Belum ada pemasukan yang dikonfirmasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pemasukans->hasPages())
            <div class="pagination-wrap">
                {{ $pemasukans->links() }}
            </div>
        @endif

    </div>

    <!-- TABEL PENGELUARAN  -->
    <!-- <div class="section-card"> -->
        <div class="section-card-header">
            <div>
                <p class="section-card-title">Rekap Pengeluaran</p>
                <p class="section-card-subtitle">
                    Pengeluaran operasional yang dicatat admin
                </p>
            </div>
            <a href="/admin/pengeluaran/create" class="btn-tambah">
                + Tambah Pengeluaran
            </a>
        </div>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kategori</th>
                    <th>Tanggal Keluar</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluarans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <span>{{ $p->kategori }}</span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    </td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td style="color:#666; font-size:0.83rem;">
                        {{ $p->keterangan ?? '-' }}
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem;">
                            <a href="/admin/pengeluaran/{{ $p->id_pengeluaran }}/edit"
                               class="btn-edit">
                                Edit
                            </a>
                            {{-- Trigger modal hapus --}}
                            <button type="button"
                                    class="btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHapus"
                                    data-id="{{ $p->id_pengeluaran }}"
                                    data-nama="{{ $p->kategori }} — {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                        Belum ada catatan pengeluaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pengeluarans->hasPages())
            <div class="pagination-wrap">
                {{ $pengeluarans->links() }}
            </div>
        @endif
    <!-- </div> -->

{{-- ===== MODAL HAPUS ===== --}}
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
                <p>Yakin ingin menghapus pengeluaran:</p>
                <p style="font-weight:600; color:#1a1a1a;" id="modal-hapus-nama">—</p>
                <p style="color:#aaa; font-size:0.78rem;">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                <button type="button"
                        class="btn-admin-cancel"
                        data-bs-dismiss="modal">
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
    // Isi modal hapus dengan data pengeluaran yang dipilih
    const modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function (e) {
        const btn  = e.relatedTarget;
        const id   = btn.dataset.id;
        const nama = btn.dataset.nama;

        document.getElementById('modal-hapus-nama').textContent = nama;
        document.getElementById('form-hapus').action =
            '/admin/pengeluaran/' + id;
    });
</script>
@endpush
