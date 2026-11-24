@extends('layouts.parent')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Semua pemberitahuan aktivitas layanan pengasuhan Anda')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Header action --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.85rem;color:#6B7280;">
            <strong style="color:#111827;">{{ $notifications->total() }}</strong> total notifikasi
        </p>
        @if($notifications->total() > 0)
            <form action="{{ route('parent.notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit"
                    style="background:#EDE9FE;color:#7B2FBE;font-size:0.78rem;font-weight:700;padding:7px 14px;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Daftar notifikasi --}}
    @forelse($notifications as $notif)
        <div style="background:#fff;border-radius:16px;padding:16px 20px;box-shadow:0 1px 6px rgba(0,0,0,0.05);border:1px solid {{ $notif->sudah_dibaca ? '#F3F4F6' : '#DDD6FE' }};
            {{ !$notif->sudah_dibaca ? 'border-left:4px solid #7B2FBE;' : '' }}
            display:flex;align-items:flex-start;gap:14px;">

            {{-- Icon tipe --}}
            <div style="width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.2rem;
                @if($notif->tipe === 'sensor_alert') background:#FEE2E2;
                @elseif($notif->tipe === 'layanan') background:#EDE9FE;
                @else background:#F3F4F6; @endif">
                @if($notif->tipe === 'sensor_alert')
                    <i class="fas fa-exclamation-triangle" style="color:#DC2626;"></i>
                @elseif($notif->tipe === 'layanan')
                    <i class="fas fa-clipboard-check" style="color:#7B2FBE;"></i>
                @else
                    <i class="fas fa-bell" style="color:#6B7280;"></i>
                @endif
            </div>

            {{-- Konten --}}
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                    <h4 style="font-weight:{{ $notif->sudah_dibaca ? '600' : '700' }};font-size:0.9rem;color:#111827;">
                        {{ $notif->judul ?? ucfirst(str_replace('_', ' ', $notif->tipe)) }}
                    </h4>
                    @if(!$notif->sudah_dibaca)
                        <span style="width:8px;height:8px;background:#7B2FBE;border-radius:50%;flex-shrink:0;"></span>
                    @endif
                </div>
                <p style="font-size:0.8rem;color:#6B7280;margin-top:4px;line-height:1.5;">{{ $notif->pesan }}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;">
                    <span style="font-size:0.68rem;color:#9CA3AF;">
                        <i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                    </span>
                    @if(!$notif->sudah_dibaca)
                        <form action="{{ route('parent.notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="font-size:0.72rem;color:#7B2FBE;font-weight:600;background:none;border:none;cursor:pointer;">
                                Tandai Dibaca
                            </button>
                        </form>
                    @else
                        <span style="font-size:0.68rem;color:#22C55E;font-weight:600;">
                            <i class="fas fa-check"></i> Sudah dibaca
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:3rem 0;background:#fff;border-radius:20px;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
            <div style="width:64px;height:64px;background:#EDE9FE;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem;color:#7B2FBE;">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 style="font-weight:700;color:#374151;">Belum Ada Notifikasi</h3>
            <p style="font-size:0.85rem;color:#9CA3AF;margin-top:4px;">Semua aktivitas layanan akan muncul di sini.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div>{{ $notifications->links() }}</div>
</div>
@endsection
