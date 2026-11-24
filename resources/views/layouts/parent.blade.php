@extends('layouts.app')

@section('sidebar-nav')
    {{-- Beranda --}}
    <a href="{{ route('parent.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home w-5"></i>
        <span>Beranda</span>
    </a>

    {{-- Cari Pengasuh --}}
    <a href="{{ route('parent.caregivers.index') }}"
       class="sidebar-link {{ request()->routeIs('parent.caregivers.*') ? 'active' : '' }}">
        <i class="fas fa-user-nurse w-5"></i>
        <span>Cari Pengasuh</span>
    </a>

    {{-- Data Anak --}}
    <a href="{{ route('parent.children.index') }}"
       class="sidebar-link {{ request()->routeIs('parent.children.*') ? 'active' : '' }}">
        <i class="fas fa-child w-5"></i>
        <span>Data Anak &amp; Wearable</span>
    </a>

    {{-- Riwayat Sesi --}}
    <a href="{{ route('parent.sessions.index') }}"
       class="sidebar-link {{ request()->routeIs('parent.sessions.index') ? 'active' : '' }}">
        <i class="fas fa-history w-5"></i>
        <span>Riwayat Sesi</span>
    </a>

    <div class="sidebar-divider"></div>

    {{-- Live Monitor jika ada sesi aktif --}}
    @php
        $activeSession = \App\Models\CareSession::where('parent_id', auth()->id())->where('status', 'berjalan')->first();
    @endphp
    @if($activeSession)
        <div class="sidebar-section-label sidebar-section-label--spaced">LIVE MONITORING</div>
        <a href="{{ route('parent.sessions.monitor', $activeSession->id) }}" class="sidebar-live-link">
            <i class="fas fa-heartbeat"></i>
            <span>Pantau {{ $activeSession->child->nama }}</span>
        </a>
    @else
        <div class="sidebar-section-label">ORANG TUA</div>
    @endif
@endsection
