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

    {{-- Divider --}}
    <div style="border-top:1px solid rgba(255,255,255,0.15);margin:10px 0;"></div>

    {{-- Live Monitor jika ada sesi aktif --}}
    @php
        $activeSession = \App\Models\CareSession::where('parent_id', auth()->id())->where('status', 'berjalan')->first();
    @endphp
    @if($activeSession)
        <div style="padding:4px 16px 6px;font-size:0.65rem;font-weight:700;color:rgba(233,213,255,0.5);letter-spacing:0.08em;text-transform:uppercase;">
            LIVE MONITORING
        </div>
        <a href="{{ route('parent.sessions.monitor', $activeSession->id) }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:12px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-weight:700;font-size:0.85rem;box-shadow:0 4px 15px rgba(220,38,38,0.4);animation:pulse 2s infinite;">
            <i class="fas fa-heartbeat"></i>
            <span>Pantau {{ $activeSession->child->nama }}</span>
        </a>
    @else
        <div style="padding:4px 16px;font-size:0.65rem;font-weight:700;color:rgba(233,213,255,0.5);letter-spacing:0.08em;text-transform:uppercase;">
            ORANG TUA
        </div>
    @endif
@endsection
