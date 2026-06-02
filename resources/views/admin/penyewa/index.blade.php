@extends('layouts.admin')

@section('title', 'Data Penyewa — Kos Faira')

@section('content')

    <h1 class="page-title">Data Penyewa</h1>
    <p class="page-subtitle">Penyewa yang pernah atau sedang aktif di Rumah Kos Faira</p>
  
    {{-- ===== TABEL DATA PENYEWA ===== --}}
    <!-- <div class="section-card mb-4"> -->
        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Penyewa</th>
                    <th>No. Kamar</th>
                    <th>No. Telepon</th>
                    <th>Tgl Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyewas as $i => $p)
                @php
                    $reservasiAktif = $p->reservasis->where('status', 'Aktif')->first()
                                  ?? $p->reservasis->sortByDesc('created_at')->first();
                @endphp
                <tr>
                    <td>{{ $penyewas->firstItem() + $loop->index }}</td>
                    <td style="font-weight:500;">{{ $p->nama }}</td>
                    <td>
                        {{ $reservasiAktif->kamar->nomor_kamar ?? '-' }}
                        @if($reservasiAktif && $reservasiAktif->kamar)
                            <span style="color:#aaa; font-size:0.78rem;">
                                ({{ $reservasiAktif->kamar->tipe_kamar }})
                            </span>
                        @endif
                    </td>
                    <td>{{ $p->no_telp ?? '-' }}</td>
                    <td>
                        {{ $reservasiAktif
                            ? \Carbon\Carbon::parse($reservasiAktif->tanggal_masuk)->format('d M Y')
                            : '-' }}
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        <a href="/admin/penyewa/{{ $p->id_penyewa }}/edit"
                           class="btn-detail">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        style="text-align:center; padding:2rem; color:#aaa; font-size:0.85rem;">
                        Belum ada data penyewa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($penyewas->hasPages())
            <div class="pagination-wrap">
                {{ $penyewas->links() }}
            </div>
        @endif
    <!-- </div> -->

@endsection