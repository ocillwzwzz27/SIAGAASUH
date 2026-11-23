@extends('layouts.app')

@section('sidebar-nav')
    <a href="{{ route('caregiver.dashboard') }}" class="sidebar-link {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home w-5"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('caregiver.sessions.index') }}" class="sidebar-link {{ request()->routeIs('caregiver.sessions.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list w-5"></i>
        <div class="flex-1 flex items-center justify-between">
            <span>Sesi Pengasuhan</span>
            @php
                $pendingRequests = \App\Models\CareSession::where('caregiver_id', auth()->id())->where('status', 'menunggu')->count();
            @endphp
            @if($pendingRequests > 0)
                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingRequests }}</span>
            @endif
        </div>
    </a>
    <a href="{{ route('caregiver.profile.edit') }}" class="sidebar-link {{ request()->routeIs('caregiver.profile.*') ? 'active' : '' }}">
        <i class="fas fa-id-card w-5"></i>
        <span>Profil & Dokumen KTP</span>
    </a>
@endsection
