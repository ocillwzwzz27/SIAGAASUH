@extends('layouts.app')

@section('sidebar-nav')
    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie w-5"></i>
        <span>Dashboard</span>
    </a>

    {{-- Verifikasi Pengasuh --}}
    <a href="{{ route('admin.caregiver.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.caregiver.*') ? 'active' : '' }}">
        <i class="fas fa-user-check w-5"></i>
        <div class="flex-1 flex items-center justify-between">
            <span>Verifikasi Pengasuh</span>
            @php $pendingCount = \App\Models\CaregiverProfile::where('status_verifikasi','menunggu')->count(); @endphp
            @if($pendingCount > 0)
                <span style="background:#FBBF24;color:#78350F;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px;">
                    {{ $pendingCount }}
                </span>
            @endif
        </div>
    </a>

    {{-- Monitor Sesi & IoT --}}
    <a href="{{ route('admin.sessions.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
        <i class="fas fa-heartbeat w-5"></i>
        <span>Monitor Sesi &amp; IoT</span>
    </a>

    {{-- Kelola Pengguna --}}
    <a href="{{ route('admin.users.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users w-5"></i>
        <span>Kelola Pengguna</span>
    </a>

    {{-- Divider --}}
    <div style="border-top:1px solid rgba(255,255,255,0.15);margin:10px 0;"></div>

    <div style="padding:4px 16px;font-size:0.65rem;font-weight:700;color:rgba(233,213,255,0.5);letter-spacing:0.08em;text-transform:uppercase;">
        ADMIN PANEL
    </div>
@endsection
