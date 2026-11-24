<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiagaAsuh - @yield('title', 'Sistem Pengasuhan Terpercaya')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#faf5ff', 100:'#f3e8ff', 200:'#e9d5ff', 300:'#d8b4fe', 400:'#c084fc', 500:'#a855f7', 600:'#9333ea', 700:'#7e22ce', 800:'#6b21a8', 900:'#581c87' },
                        siaga: { DEFAULT:'#7B2FBE', dark:'#5B1D92', light:'#A855F7', accent:'#F59E0B' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .gradient-purple { background: linear-gradient(135deg, #5B1D92 0%, #7B2FBE 60%, #9333EA 100%); }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:12px; color:rgba(233,213,255,0.9); transition:all 0.2s; font-size:0.875rem; }
        .sidebar-link:hover { background:rgba(255,255,255,0.18); color:#fff; }
        .sidebar-link.active { background:rgba(255,255,255,0.22); color:#fff; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.15); }
        .card { background:#fff; border-radius:20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); border:1px solid #f1f0fb; padding:1.5rem; }
        .btn-primary { display:inline-flex; align-items:center; justify-content:center; background:#7B2FBE; color:#fff; padding:10px 20px; border-radius:12px; font-weight:600; font-size:0.875rem; transition:all 0.2s; border:none; cursor:pointer; }
        .btn-primary:hover { background:#5B1D92; box-shadow:0 4px 15px rgba(123,47,190,0.4); transform:translateY(-1px); }
        .btn-outline { display:inline-flex; align-items:center; justify-content:center; border:2px solid #7B2FBE; color:#7B2FBE; padding:9px 20px; border-radius:12px; font-weight:600; font-size:0.875rem; transition:all 0.2s; background:transparent; cursor:pointer; }
        .btn-outline:hover { background:#7B2FBE; color:#fff; }
        .badge-verified { background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; }
        .badge-pending { background:#fef9c3; color:#854d0e; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; }
        .badge-rejected { background:#fee2e2; color:#991b1b; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; }
        .badge-active { background:#dbeafe; color:#1d4ed8; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; }
        .input { width:100%; border:1.5px solid #e5e7eb; border-radius:12px; padding:10px 16px; font-size:0.875rem; outline:none; transition:all 0.2s; background:#fff; }
        .input:focus { border-color:#a855f7; box-shadow:0 0 0 3px rgba(168,85,247,0.15); }
        .label { display:block; font-size:0.8rem; font-weight:600; color:#374151; margin-bottom:6px; }
        /* Pagination */
        nav[aria-label="Pagination"] { display:flex; justify-content:center; margin-top:16px; }
        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:#f9f5ff; }
        ::-webkit-scrollbar-thumb { background:#c4b5fd; border-radius:999px; }
        /* Transition page */
        main { animation: fadeInUp 0.25s ease; }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">

{{-- SIDEBAR --}}
<div class="flex h-screen overflow-hidden">
    <aside id="sidebar" class="gradient-purple w-64 flex-shrink-0 flex flex-col shadow-xl transition-all duration-300 z-30">
        {{-- Logo --}}
        <div class="p-5 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div style="width:40px;height:40px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;padding:4px;">
                    <img src="{{ asset('images/logo-full.png') }}" alt="SiagaAsuh"
                         style="width:100%;height:100%;object-fit:contain;">
                </div>
                <div>
                    <h1 style="color:#fff;font-weight:800;font-size:1.05rem;line-height:1.2;letter-spacing:-0.3px;">
                        Siaga<span style="color:#F59E0B;">Asuh</span>
                    </h1>
                    <p style="color:rgba(233,213,255,0.75);font-size:0.68rem;text-transform:capitalize;">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            @yield('sidebar-nav')
        </nav>

        {{-- User info --}}
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

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between" style="box-shadow:0 1px 10px rgba(0,0,0,0.05);">
            <div>
                <h2 class="text-gray-800 font-bold text-lg">@yield('page-title', 'Dashboard')</h2>
                <p class="text-gray-400 text-xs mt-0.5">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notifikasi --}}
                <div class="relative">
                    @php $unread = \App\Models\AppNotification::where('user_id', auth()->id())->where('sudah_dibaca', false)->count(); @endphp
                    @php $notifRoute = auth()->user()->role === 'orang_tua' ? route('parent.notifications.index') : '#'; @endphp
                    <a href="{{ $notifRoute }}" style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:12px;color:#6B7280;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#F5F3FF';this.style.color='#7B2FBE';" onmouseout="this.style.background='transparent';this.style.color='#6B7280';" title="Notifikasi">
                        <i class="fas fa-bell" style="font-size:1rem;"></i>
                        @if($unread > 0)
                            <span style="position:absolute;top:-2px;right:-2px;background:#EF4444;color:#fff;font-size:9px;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;line-height:1;border:2px solid #fff;">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </a>
                </div>
                {{-- User Chip --}}
                <div class="flex items-center gap-2 bg-purple-50 px-3 py-1.5 rounded-xl">
                    <div class="w-7 h-7 bg-siaga text-white rounded-lg flex items-center justify-center font-bold text-xs" style="background:#7B2FBE;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-xs font-semibold text-gray-700 hidden sm:block">{{ Str::limit(auth()->user()->name, 18) }}</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
