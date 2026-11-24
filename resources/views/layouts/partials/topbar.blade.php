@php
    $unread = \App\Models\AppNotification::where('user_id', auth()->id())->where('sudah_dibaca', false)->count();
    $notifRoute = auth()->user()->role === 'orang_tua' ? route('parent.notifications.index') : '#';
@endphp

<header class="topbar bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
    <div>
        <h2 class="text-gray-800 font-bold text-lg">@yield('page-title', 'Dashboard')</h2>
        <p class="text-gray-400 text-xs mt-0.5">@yield('page-subtitle', '')</p>
    </div>

    <div class="flex items-center gap-3">
        {{-- Notifikasi --}}
        <a href="{{ $notifRoute }}" class="topbar-bell" title="Notifikasi">
            <i class="fas fa-bell"></i>
            @if($unread > 0)
                <span class="topbar-bell__badge">{{ $unread > 9 ? '9+' : $unread }}</span>
            @endif
        </a>

        {{-- Chip pengguna --}}
        <div class="flex items-center gap-2 bg-purple-50 px-3 py-1.5 rounded-xl">
            <div class="w-7 h-7 bg-siaga text-white rounded-lg flex items-center justify-center font-bold text-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="text-xs font-semibold text-gray-700 hidden sm:block">{{ Str::limit(auth()->user()->name, 18) }}</span>
        </div>
    </div>
</header>
