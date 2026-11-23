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
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <span class="text-siaga text-xl">👶</span>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">SiagaAsuh</h1>
                    <p class="text-purple-200 text-xs">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
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
                    <button class="relative p-2.5 text-gray-500 hover:text-purple-700 hover:bg-purple-50 rounded-xl transition" title="Notifikasi">
                        <i class="fas fa-bell"></i>
                        @if($unread > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] w-4.5 h-4.5 rounded-full flex items-center justify-center font-bold leading-none" style="width:18px;height:18px;font-size:9px;">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </button>
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
