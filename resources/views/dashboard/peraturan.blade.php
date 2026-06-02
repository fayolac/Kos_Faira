@extends('layouts.app')

@section('title', 'Peraturan — Rumah Kos Faira')

@section('content')

<div class="container section-public">

    <h1 class="page-public-title">Peraturan Kos Faira</h1>
    <p class="page-public-subtitle">
        Peraturan berikut wajib dipatuhi oleh seluruh penghuni Rumah Kos Faira
    </p>

    <div class="table-responsive">
        <table class="peraturan-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th style="width:200px;">Judul Peraturan</th>
                            <th>Isi Peraturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peraturans as $i => $p)
                        <tr>
                            <td>{{ $i + 1}}</td>
                            <td style="font-weight:500;">{{ $p->judul }}</td>
                            <td style="font-size:0.83rem; color:#555; line-height:1.6;">
                                {{ Str::limit($p->isi, 120) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="peraturan-empty">Belum ada peraturan yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    <tbody>
        </table>
    </div>

</div>

@endsection