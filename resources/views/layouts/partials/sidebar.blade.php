<aside id="sidebar" class="gradient-purple w-64 flex-shrink-0 flex flex-col shadow-xl transition-all duration-300 z-30">
    {{-- Logo --}}
    <div class="p-5 border-b border-white/20">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo-full.png') }}" alt="SiagaAsuh">
            </div>
            <div>
                <h1 class="sidebar-title">Siaga<span class="sidebar-title__accent">Asuh</span></h1>
                <p class="sidebar-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
        </div>
    </div>

    {{-- Navigasi (diisi layout per-role) --}}
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @yield('sidebar-nav')
    </nav>

    {{-- Info pengguna --}}
    <div class="p-4 border-t border-white/20">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-purple-200 text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left sidebar-link text-sm py-2">
                <i class="fas fa-sign-out-alt w-4"></i> Keluar
            </button>
        </form>
    </div>
</aside>
