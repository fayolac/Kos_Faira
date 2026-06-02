@extends('layouts.admin')

@section('title', 'Data Pengaduan — Kos Faira')

@section('content')

    <h1 class="page-title">Data Pengaduan</h1>
    <p class="page-subtitle">Daftar pengaduan dari penyewa</p>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Penyewa</th>
                    <th>No. Kamar</th>
                    <th>Judul Pengaduan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduans as $i => $p)
                <tr>
                    <td>{{ $pengaduans->firstItem() + $i }}</td>
                    <td>{{ $p->reservasi->penyewa->nama ?? '-' }}</td>
                    <td>{{ $p->reservasi->kamar->nomor_kamar ?? '-' }}</td>
                    <td style="font-weight:500;">{{ $p->judul }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_pengaduan)->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        <a href="/admin/pengaduan/{{ $p->id_pengaduan }}/edit"
                           class="btn-edit">
                            Detail & Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                        Belum ada pengaduan dari penyewa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($pengaduans->hasPages())
            <div class="pagination-wrap">
                {{ $pengaduans->links() }}
            </div>
        @endif

@endsection