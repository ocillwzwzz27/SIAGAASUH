@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Ringkasan aktivitas platform dan pemantauan sistem SiagaAsuh')

@section('content')
<div class="space-y-6">

    {{-- Grid Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Pengguna --}}
        <div class="stat-card stat-card--primary">
            <div class="stat-card__bg-icon"><i class="fas fa-users"></i></div>
            <p class="stat-card__label">Total Pengguna</p>
            <h3 class="stat-card__value">{{ $stats['total_pengguna'] }}</h3>
            <p class="stat-card__meta">{{ $stats['total_orang_tua'] }} Ortu &middot; {{ $stats['total_pengasuh'] }} Pengasuh</p>
            <div class="stat-card__chip">
                <i class="fas fa-arrow-up stat-card__chip-icon"></i> Aktif di platform
            </div>
        </div>

        {{-- Menunggu Verifikasi --}}
        <div class="stat-card stat-card--warning">
            <div class="stat-card__bg-icon"><i class="fas fa-user-clock"></i></div>
            <p class="stat-card__label">Menunggu Verifikasi</p>
            <h3 class="stat-card__value">{{ $stats['pengasuh_menunggu'] }}</h3>
            @if($stats['pengasuh_menunggu'] > 0)
                <a href="{{ route('admin.caregiver.index') }}" class="stat-card__link">
                    <i class="fas fa-arrow-right"></i> Tinjau sekarang
                </a>
            @else
                <p class="stat-card__meta stat-card__meta--ok"><i class="fas fa-check"></i> Semua selesai</p>
            @endif
        </div>

        {{-- Pengasuh Terverifikasi --}}
        <div class="stat-card stat-card--success">
            <div class="stat-card__bg-icon"><i class="fas fa-user-check"></i></div>
            <p class="stat-card__label">Pengasuh Terverifikasi</p>
            <h3 class="stat-card__value">{{ $stats['pengasuh_terverifikasi'] }}</h3>
            <p class="stat-card__meta stat-card__meta--ok"><i class="fas fa-check-circle"></i> Aktif &amp; Siap Melayani</p>
        </div>

        {{-- Sesi Aktif --}}
        <div class="stat-card stat-card--danger">
            <div class="stat-card__bg-icon"><i class="fas fa-heartbeat"></i></div>
            <p class="stat-card__label">Sesi Sedang Berjalan</p>
            <h3 class="stat-card__value">{{ $stats['sesi_aktif'] }}</h3>
            <p class="stat-card__meta">Dari total {{ $stats['total_sesi'] }} sesi tercatat</p>
            @if($stats['sesi_aktif'] > 0)
                <a href="{{ route('admin.sessions.index') }}" class="stat-card__chip stat-card__chip--link">
                    <i class="fas fa-eye"></i> Pantau Live
                </a>
            @endif
        </div>
    </div>

    {{-- Konten Utama 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pengasuh Perlu Verifikasi --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="icon-box icon-box--warning"><i class="fas fa-user-clock"></i></div>
                    <div>
                        <h3 class="panel-title">Pengasuh Perlu Verifikasi</h3>
                        @if(!$pengasuh_menunggu->isEmpty())
                            <p class="panel-subtitle panel-subtitle--warning">{{ $pengasuh_menunggu->count() }} menunggu tindakan</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.caregiver.index') }}" class="panel-link">Lihat Semua →</a>
            </div>

            @if($pengasuh_menunggu->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__icon"><i class="fas fa-check-double"></i></div>
                    <p class="empty-state__text">Semua pengasuh sudah ditinjau!</p>
                </div>
            @else
                <div>
                    @foreach($pengasuh_menunggu as $item)
                        <div class="list-row">
                            <div class="list-row__main">
                                <div class="avatar">{{ strtoupper(substr($item->user->name, 0, 1)) }}</div>
                                <div>
                                    <h4 class="list-row__title">{{ $item->user->name }}</h4>
                                    <p class="list-row__meta">NIK: {{ $item->nik ?? '-' }} &middot; {{ $item->user->phone }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.caregiver.show', $item->id) }}" class="btn-chip">
                                <i class="fas fa-eye"></i> Tinjau
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sesi Pengasuhan Terkini --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="icon-box icon-box--brand"><i class="fas fa-heartbeat"></i></div>
                    <div>
                        <h3 class="panel-title">Sesi Pengasuhan Terkini</h3>
                        <p class="panel-subtitle">5 aktivitas terakhir</p>
                    </div>
                </div>
                <a href="{{ route('admin.sessions.index') }}" class="panel-link">Monitor Semua →</a>
            </div>

            @if($sesi_terbaru->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__glyph"><i class="fas fa-calendar-times"></i></div>
                    <p class="empty-state__text">Belum ada riwayat sesi.</p>
                </div>
            @else
                <div>
                    @foreach($sesi_terbaru as $s)
                        @php
                            $pill = in_array($s->status, ['berjalan', 'selesai', 'menunggu'], true) ? $s->status : 'default';
                        @endphp
                        <div class="list-row">
                            <div>
                                <div class="list-row__title-line">
                                    <h4 class="list-row__title">{{ $s->child->nama }}</h4>
                                    <span class="status-pill status-pill--{{ $pill }}">{{ $s->status }}</span>
                                </div>
                                <p class="list-row__meta">{{ $s->parent->name }} &rsaquo; {{ $s->caregiver->name }}</p>
                            </div>
                            <a href="{{ route('admin.sessions.show', $s->id) }}" class="list-row__chevron">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Info Cards bawah --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="info-card info-card--success">
            <div class="info-card__head">
                <i class="fas fa-shield-alt info-card__icon"></i>
                <h4 class="info-card__title">Sistem Aman</h4>
            </div>
            <p class="info-card__text">Semua data dienkripsi dan sistem berjalan normal.</p>
        </div>
        <div class="info-card info-card--warning">
            <div class="info-card__head">
                <i class="fas fa-microchip info-card__icon"></i>
                <h4 class="info-card__title">IoT Gateway</h4>
            </div>
            <p class="info-card__text">Endpoint <code>POST /api/v1/sensor</code> aktif menerima data wearable.</p>
        </div>
        <div class="info-card info-card--brand">
            <div class="info-card__head">
                <i class="fas fa-database info-card__icon"></i>
                <h4 class="info-card__title">Database</h4>
            </div>
            <p class="info-card__text">db_siagasuh &mdash; 7 tabel, {{ $stats['total_pengguna'] }} pengguna terdaftar.</p>
        </div>
    </div>

</div>
@endsection
