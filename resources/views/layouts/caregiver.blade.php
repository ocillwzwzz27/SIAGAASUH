@extends('layouts.app')

@section('sidebar-nav')
    {{-- Dashboard --}}
    <a href="{{ route('caregiver.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home w-5"></i>
        <span>Dashboard</span>
    </a>

    {{-- Sesi Pengasuhan --}}
    <a href="{{ route('caregiver.sessions.index') }}"
       class="sidebar-link {{ request()->routeIs('caregiver.sessions.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list w-5"></i>
        <div class="flex-1 flex items-center justify-between">
            <span>Sesi Pengasuhan</span>
            @php
                $pendingRequests = \App\Models\CareSession::where('caregiver_id', auth()->id())->where('status', 'menunggu')->count();
            @endphp
            @if($pendingRequests > 0)
                <span class="sidebar-count">{{ $pendingRequests }}</span>
            @endif
        </div>
    </a>

    {{-- Profil & Dokumen --}}
    <a href="{{ route('caregiver.profile.edit') }}"
       class="sidebar-link {{ request()->routeIs('caregiver.profile.*') ? 'active' : '' }}">
        <i class="fas fa-id-card w-5"></i>
        <span>Profil &amp; Dokumen KTP</span>
    </a>

    <div class="sidebar-divider"></div>
    <div class="sidebar-section-label">MITRA PENGASUH</div>
@endsection
