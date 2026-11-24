@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Ringkasan aktivitas platform dan pemantauan sistem SiagaAsuh')

@section('content')
<div class="space-y-6">

    {{-- Grid Statistik dengan Gradien --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Pengguna --}}
        <div style="background:linear-gradient(135deg,#7B2FBE,#5B1D92);border-radius:20px;padding:1.25rem;color:#fff;position:relative;overflow:hidden;box-shadow:0 8px 25px rgba(123,47,190,0.35);">
            <div style="position:absolute;right:-15px;top:-15px;font-size:5rem;opacity:0.1;"><i class="fas fa-users"></i></div>
            <p style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(233,213,255,0.8);">Total Pengguna</p>
            <h3 style="font-size:2.2rem;font-weight:900;margin:4px 0;">{{ $stats['total_pengguna'] }}</h3>
            <p style="font-size:0.72rem;color:rgba(233,213,255,0.7);">{{ $stats['total_orang_tua'] }} Ortu &middot; {{ $stats['total_pengasuh'] }} Pengasuh</p>
            <div style="margin-top:12px;background:rgba(255,255,255,0.15);border-radius:8px;padding:6px 10px;display:inline-flex;align-items:center;gap:6px;font-size:0.7rem;font-weight:600;">
                <i class="fas fa-arrow-up" style="color:#4ADE80;"></i> Aktif di platform
            </div>
        </div>

        {{-- Menunggu Verifikasi --}}
        <div style="background:#fff;border-radius:20px;padding:1.25rem;position:relative;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.06);border:1px solid #FEF9C3;border-left:5px solid #FBBF24;">
            <div style="position:absolute;right:-10px;top:-10px;font-size:4.5rem;opacity:0.07;color:#F59E0B;"><i class="fas fa-user-clock"></i></div>
            <p style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#92400E;">Menunggu Verifikasi</p>
            <h3 style="font-size:2.2rem;font-weight:900;color:#78350F;margin:4px 0;">{{ $stats['pengasuh_menunggu'] }}</h3>
            @if($stats['pengasuh_menunggu'] > 0)
                <a href="{{ route('admin.caregiver.index') }}" style="font-size:0.72rem;color:#D97706;font-weight:700;display:inline-flex;align-items:center;gap:4px;margin-top:4px;">
                    <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i> Tinjau sekarang
                </a>
            @else
                <p style="font-size:0.72rem;color:#15803d;margin-top:4px;"><i class="fas fa-check"></i> Semua selesai</p>
            @endif
        </div>

        {{-- Pengasuh Terverifikasi --}}
        <div style="background:#fff;border-radius:20px;padding:1.25rem;position:relative;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.06);border:1px solid #DCFCE7;border-left:5px solid #22C55E;">
            <div style="position:absolute;right:-10px;top:-10px;font-size:4.5rem;opacity:0.07;color:#22C55E;"><i class="fas fa-user-check"></i></div>
            <p style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#166534;">Pengasuh Terverifikasi</p>
            <h3 style="font-size:2.2rem;font-weight:900;color:#15803d;margin:4px 0;">{{ $stats['pengasuh_terverifikasi'] }}</h3>
            <p style="font-size:0.72rem;color:#15803d;margin-top:4px;"><i class="fas fa-check-circle"></i> Aktif &amp; Siap Melayani</p>
        </div>

        {{-- Sesi Aktif --}}
        <div style="background:linear-gradient(135deg,#EC4899,#EF4444);border-radius:20px;padding:1.25rem;color:#fff;position:relative;overflow:hidden;box-shadow:0 8px 25px rgba(239,68,68,0.35);">
            <div style="position:absolute;right:-15px;top:-15px;font-size:5rem;opacity:0.1;"><i class="fas fa-heartbeat"></i></div>
            <p style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(254,205,211,0.9);">Sesi Sedang Berjalan</p>
            <h3 style="font-size:2.2rem;font-weight:900;margin:4px 0;">{{ $stats['sesi_aktif'] }}</h3>
            <p style="font-size:0.72rem;color:rgba(254,205,211,0.8);">Dari total {{ $stats['total_sesi'] }} sesi tercatat</p>
            @if($stats['sesi_aktif'] > 0)
                <a href="{{ route('admin.sessions.index') }}" style="margin-top:12px;background:rgba(255,255,255,0.2);border-radius:8px;padding:6px 10px;display:inline-flex;align-items:center;gap:6px;font-size:0.7rem;font-weight:600;color:#fff;">
                    <i class="fas fa-eye"></i> Pantau Live
                </a>
            @endif
        </div>
    </div>

    {{-- Konten Utama 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pengasuh Perlu Verifikasi --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div style="width:36px;height:36px;background:#FEF9C3;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#D97706;font-size:1rem;">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div>
                        <h3 style="font-weight:700;color:#1F2937;font-size:0.95rem;">Pengasuh Perlu Verifikasi</h3>
                        @if(!$pengasuh_menunggu->isEmpty())
                            <p style="font-size:0.7rem;color:#D97706;font-weight:600;">{{ $pengasuh_menunggu->count() }} menunggu tindakan</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.caregiver.index') }}" style="font-size:0.75rem;color:#7B2FBE;font-weight:700;">Lihat Semua →</a>
            </div>

            @if($pengasuh_menunggu->isEmpty())
                <div style="text-align:center;padding:2rem 0;color:#9CA3AF;">
                    <div style="width:52px;height:52px;background:#D1FAE5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:1.4rem;color:#22C55E;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p style="font-size:0.85rem;">Semua pengasuh sudah ditinjau!</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach($pengasuh_menunggu as $item)
                        <div style="padding:12px 0;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F3F4F6;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:40px;height:40px;background:linear-gradient(135deg,#A855F7,#7B2FBE);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 style="font-weight:600;font-size:0.875rem;color:#111827;">{{ $item->user->name }}</h4>
                                    <p style="font-size:0.72rem;color:#6B7280;">NIK: {{ $item->nik ?? '-' }} &middot; {{ $item->user->phone }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.caregiver.show', $item->id) }}"
                               style="background:#7B2FBE;color:#fff;font-size:0.72rem;font-weight:700;padding:6px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:4px;text-decoration:none;">
                                <i class="fas fa-eye" style="font-size:0.65rem;"></i> Tinjau
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sesi Pengasuhan Terkini --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div style="width:36px;height:36px;background:#EDE9FE;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#7B2FBE;font-size:1rem;">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div>
                        <h3 style="font-weight:700;color:#1F2937;font-size:0.95rem;">Sesi Pengasuhan Terkini</h3>
                        <p style="font-size:0.7rem;color:#9CA3AF;">5 aktivitas terakhir</p>
                    </div>
                </div>
                <a href="{{ route('admin.sessions.index') }}" style="font-size:0.75rem;color:#7B2FBE;font-weight:700;">Monitor Semua →</a>
            </div>

            @if($sesi_terbaru->isEmpty())
                <div style="text-align:center;padding:2rem 0;color:#9CA3AF;">
                    <div style="font-size:2.5rem;margin-bottom:8px;"><i class="fas fa-calendar-times"></i></div>
                    <p style="font-size:0.85rem;">Belum ada riwayat sesi.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach($sesi_terbaru as $s)
                        <div style="padding:11px 0;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F3F4F6;">
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <h4 style="font-weight:600;font-size:0.875rem;color:#111827;">{{ $s->child->nama }}</h4>
                                    <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px;text-transform:uppercase;
                                        @if($s->status === 'berjalan') background:#DBEAFE;color:#1D4ED8;
                                        @elseif($s->status === 'selesai') background:#DCFCE7;color:#15803D;
                                        @elseif($s->status === 'menunggu') background:#FEF9C3;color:#854D0E;
                                        @else background:#F3F4F6;color:#6B7280; @endif">
                                        {{ $s->status }}
                                    </span>
                                </div>
                                <p style="font-size:0.72rem;color:#6B7280;margin-top:2px;">
                                    {{ $s->parent->name }} &rsaquo; {{ $s->caregiver->name }}
                                </p>
                            </div>
                            <a href="{{ route('admin.sessions.show', $s->id) }}" style="color:#9CA3AF;font-size:0.85rem;padding:6px;">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Info Cards bawah --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div style="background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border-radius:16px;padding:1.25rem;border:1px solid #BBF7D0;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <i class="fas fa-shield-alt" style="color:#15803D;font-size:1.1rem;"></i>
                <h4 style="font-weight:700;font-size:0.85rem;color:#166534;">Sistem Aman</h4>
            </div>
            <p style="font-size:0.75rem;color:#15803d;">Semua data dienkripsi dan sistem berjalan normal.</p>
        </div>
        <div style="background:linear-gradient(135deg,#FFF7ED,#FED7AA);border-radius:16px;padding:1.25rem;border:1px solid #FDBA74;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <i class="fas fa-microchip" style="color:#C2410C;font-size:1.1rem;"></i>
                <h4 style="font-weight:700;font-size:0.85rem;color:#C2410C;">IoT Gateway</h4>
            </div>
            <p style="font-size:0.75rem;color:#9A3412;">Endpoint <code>POST /api/v1/sensor</code> aktif menerima data wearable.</p>
        </div>
        <div style="background:linear-gradient(135deg,#F5F3FF,#EDE9FE);border-radius:16px;padding:1.25rem;border:1px solid #DDD6FE;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <i class="fas fa-database" style="color:#7C3AED;font-size:1.1rem;"></i>
                <h4 style="font-weight:700;font-size:0.85rem;color:#7C3AED;">Database</h4>
            </div>
            <p style="font-size:0.75rem;color:#6D28D9;">db_siagasuh &mdash; 7 tabel, {{ $stats['total_pengguna'] }} pengguna terdaftar.</p>
        </div>
    </div>

</div>
@endsection
