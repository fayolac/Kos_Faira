@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran — Kos Faira')

@section('content')
    <h1 class="page-title">Verifikasi Pembayaran</h1>
    <p class="page-subtitle">Konfirmasi bukti pembayaran yang dikirim penyewa</p>

    <!-- <div class="section-card"> -->
        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Penyewa</th>
                    <th>No. Kamar</th>
                    <th>Bulan Tagihan</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayarans as $i => $p)
                <tr>
                    <td>{{ $pembayarans->firstItem() + $loop->index }}</td>
                    <td>{{ $p->reservasi->penyewa->nama ?? '-' }}</td>
                    <td>{{ $p->reservasi->kamar->nomor_kamar ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->bulan_tagihan)->format('M Y') }}</td>
                    <td>
                        <span class="{{ strtolower($p->tipe_pembayaran) }}">
                            {{ $p->tipe_pembayaran }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>
                        {{ $p->tanggal_bayar
                            ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y')
                            : '-' }}
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        <a href="/admin/pembayaran/{{ $p->id_pembayaran }}"
                           class="btn-detail">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9"
                        style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                        Belum ada data pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($pembayarans->hasPages())
            <div class="pagination-wrap">
                {{ $pembayarans->links() }}
            </div>
        @endif
    <!-- </div> -->

@endsection