<?php
use Illuminate\Support\Str;
?>

@extends('layouts.app')

@section('title', 'Pengaduan Penyewa — Rumah Kos Faira')

@section('content')

<link rel="stylesheet" href="{{ asset('css/penyewa.css') }}">

<div class="container penyewa-page">

    <div class="penyewa-header">
        <div>
            <h1 class="penyewa-title">Pengaduan Penyewa</h1>
            <p class="penyewa-subtitle">
                Penyewa dapat mengajukan pengaduan terkait gangguan yang dialami pada Rumah Kos Faira
            </p>
        </div>
        <a href="/pengaduan/create" class="btn-orange">+ Tambah Pengaduan</a>
    </div>

    <div class="table-penyewa-wrap">
        <table class="table-penyewa">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Deskripsi Keluhan</th>
                    <th>Tanggal Diajukan</th>
                    <th>Tanggal Update</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Tanggapan Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $p->judul }}</td>
                    <td style="max-width:220px; color:#666;">
                        {{ Str::limit($p->keluhan, 60) }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_pengaduan)->format('d M Y') }}
                    </td>
                    <td>
                        @if($p->status === 'Diajukan')
                            <span class="text-muted">-</span>
                        @else
                            {{ $p->tanggal_update? \Carbon\Carbon::parse($p->tanggal_update)->format('d M Y'): '-' }}                        
                        @endif
                    </td>
                    <td>
                        @if($p->foto)
                            <a href="{{ asset('storage/' . $p->foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $p->foto) }}"
                                     class="tabel-foto-pengaduan"
                                     alt="Foto Pengaduan">
                            </a>
                        @else
                            <span class="tabel-foto-none">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        {{$p->tanggapan_admin ?? '-'}}
                    </td>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-empty">
                        Belum ada pengaduan. Klik "+ Tambah Pengaduan" untuk mengajukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection