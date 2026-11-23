@extends('layouts.app')

@section('sidebar-nav')
    <a href="{{ route('parent.dashboard') }}" class="sidebar-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home w-5"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('parent.caregivers.index') }}" class="sidebar-link {{ request()->routeIs('parent.caregivers.*') ? 'active' : '' }}">
        <i class="fas fa-user-nurse w-5"></i>
        <span>Cari Pengasuh</span>
    </a>
    <a href="{{ route('parent.children.index') }}" class="sidebar-link {{ request()->routeIs('parent.children.*') ? 'active' : '' }}">
        <i class="fas fa-child w-5"></i>
        <span>Data Anak & Wearable</span>
    </a>
    <a href="{{ route('parent.sessions.index') }}" class="sidebar-link {{ request()->routeIs('parent.sessions.index') ? 'active' : '' }}">
        <i class="fas fa-history w-5"></i>
        <span>Riwayat Sesi</span>
    </a>

    {{-- Sesi Aktif Real-time Monitoring Link --}}
    @php
        $activeSession = \App\Models\CareSession::where('parent_id', auth()->id())->where('status', 'berjalan')->first();
    @endphp
    @if($activeSession)
        <div class="pt-4 pb-2">
            <span class="text-[10px] font-bold text-purple-200 uppercase tracking-wider px-3">Live Monitor</span>
        </div>
        <a href="{{ route('parent.sessions.monitor', $activeSession->id) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-500 text-white font-bold text-sm shadow-lg animate-pulse">
            <i class="fas fa-heartbeat"></i>
            <span>Pantau {{ $activeSession->child->nama }}</span>
        </a>
    @endif
@endsection
