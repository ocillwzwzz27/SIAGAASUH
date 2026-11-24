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
                <span style="background:#FBBF24;color:#78350F;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px;">
                    {{ $pendingRequests }}
                </span>
            @endif
        </div>
    </a>

    {{-- Profil & Dokumen --}}
    <a href="{{ route('caregiver.profile.edit') }}"
       class="sidebar-link {{ request()->routeIs('caregiver.profile.*') ? 'active' : '' }}">
        <i class="fas fa-id-card w-5"></i>
        <span>Profil &amp; Dokumen KTP</span>
    </a>

    {{-- Divider --}}
    <div style="border-top:1px solid rgba(255,255,255,0.15);margin:10px 0;"></div>

    <div style="padding:4px 16px;font-size:0.65rem;font-weight:700;color:rgba(233,213,255,0.5);letter-spacing:0.08em;text-transform:uppercase;">
        MITRA PENGASUH
    </div>
@endsection
