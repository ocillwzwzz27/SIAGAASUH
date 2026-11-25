@extends('layouts.caregiver')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Pemberitahuan verifikasi & permintaan sesi')

@section('content')
<div class="space-y-4">

    {{-- Header action --}}
    <div class="notif-toolbar">
        <p class="notif-count"><strong>{{ $notifications->total() }}</strong> total notifikasi</p>
        @if($notifications->total() > 0)
            <form action="{{ route('caregiver.notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-soft">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Daftar notifikasi --}}
    @forelse($notifications as $notif)
        @php
            $iconType = match ($notif->tipe) {
                'sensor_alert' => 'alert',
                'layanan'      => 'service',
                default        => 'default',
            };
            $iconClass = match ($notif->tipe) {
                'sensor_alert' => 'fa-exclamation-triangle',
                'layanan'      => 'fa-clipboard-check',
                default        => 'fa-bell',
            };
        @endphp
        <div class="notif-item {{ $notif->sudah_dibaca ? '' : 'notif-item--unread' }}">
            <div class="notif-icon notif-icon--{{ $iconType }}">
                <i class="fas {{ $iconClass }}"></i>
            </div>

            <div class="notif-body">
                <div class="notif-head">
                    <h4 class="notif-title">{{ $notif->judul ?? ucfirst(str_replace('_', ' ', $notif->tipe)) }}</h4>
                    @if(!$notif->sudah_dibaca)
                        <span class="notif-dot"></span>
                    @endif
                </div>
                <p class="notif-message">{{ $notif->pesan }}</p>
                <div class="notif-foot">
                    <span class="notif-time"><i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</span>
                    @if(!$notif->sudah_dibaca)
                        <form action="{{ route('caregiver.notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="link-button">Tandai Dibaca</button>
                        </form>
                    @else
                        <span class="notif-read"><i class="fas fa-check"></i> Sudah dibaca</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-card">
            <div class="empty-card__icon"><i class="fas fa-bell-slash"></i></div>
            <h3 class="empty-card__title">Belum Ada Notifikasi</h3>
            <p class="empty-card__text">Semua informasi verifikasi dan permintaan sesi akan muncul di sini.</p>
        </div>
    @endforelse

    <div>{{ $notifications->links() }}</div>
</div>
@endsection
