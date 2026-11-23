@extends('layouts.app')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie w-5"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.caregiver.index') }}" class="sidebar-link {{ request()->routeIs('admin.caregiver.*') ? 'active' : '' }}">
        <i class="fas fa-user-check w-5"></i>
        <div class="flex-1 flex items-center justify-between">
            <span>Verifikasi Pengasuh</span>
            @php $pendingCount = \App\Models\CaregiverProfile::where('status_verifikasi', 'menunggu')->count(); @endphp
            @if($pendingCount > 0)
                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </div>
    </a>
    <a href="{{ route('admin.sessions.index') }}" class="sidebar-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
        <i class="fas fa-heartbeat w-5"></i>
        <span>Monitor Sesi & IoT</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users w-5"></i>
        <span>Kelola Pengguna</span>
    </a>
@endsection
