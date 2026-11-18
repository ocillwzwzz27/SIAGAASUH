@extends('layouts.app')
@section('title', 'Notifikasi - SiagaAsuh')

@php
    $tabs = ['semua' => 'Semua', 'layanan' => 'Layanan', 'jadwal' => 'Jadwal', 'sistem' => 'Sistem'];
@endphp

@section('content')
<div class="px-4 pb-10 pt-5">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('account') }}" class="sa-soft flex h-11 w-11 items-center justify-center rounded-full" aria-label="Kembali">
                <x-icon name="arrow-left" class="h-5 w-5" stroke="2.4" />
            </a>
            <h1 class="text-[28px] font-extrabold text-sa-700">Notifikasi</h1>
        </div>
        <span class="sa-tile flex h-11 w-11 items-center justify-center rounded-2xl">
            <x-icon name="bell" class="h-[22px] w-[22px]" fill="currentColor" stroke="1.5" />
        </span>
    </header>

    {{-- Tab penyaring --}}
    <nav class="mt-5 flex gap-2" aria-label="Filter notifikasi">
        @foreach ($tabs as $key => $label)
            @if ($key === $tab)
                <a href="{{ route('notifications', ['tab' => $key]) }}" class="sa-tile flex-[1.25] rounded-full py-2 text-center text-[12.5px] font-bold">{{ $label }}</a>
            @else
                <a href="{{ route('notifications', ['tab' => $key]) }}" class="flex-1 rounded-full border border-sa-200 bg-white/70 py-2 text-center text-[12.5px] font-semibold text-sa-700 shadow-[0_4px_10px_-6px_rgba(106,34,196,.5)]">{{ $label }}</a>
            @endif
        @endforeach
    </nav>

    @forelse ($groups as $heading => $items)
        <h2 class="mb-2 mt-5 text-[14px] font-bold text-sa-ink">{{ $heading }}</h2>
        <ul class="space-y-2.5">
            @foreach ($items as $n)
                <li class="flex items-start gap-3 rounded-2xl px-3.5 py-3.5 {{ $n['unread'] ? 'bg-sa-100' : 'sa-white' }}">
                    <span class="flex h-[46px] w-[46px] shrink-0 items-center justify-center rounded-xl bg-white text-sa-600 shadow-[0_4px_10px_-6px_rgba(106,34,196,.5)]">
                        <x-icon :name="$n['icon']" class="h-6 w-6" stroke="1.8" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-[14px] font-bold leading-tight text-sa-700">{{ $n['title'] }}</h3>
                            <span class="shrink-0 text-[11px] text-sa-muted">{{ $n['time'] }}</span>
                        </div>
                        <div class="mt-1 flex items-end justify-between gap-2">
                            <p class="text-[12px] leading-snug text-sa-ink/90">{!! $n['body'] !!}</p>
                            @if ($n['unread'])
                                <span class="mb-0.5 h-2.5 w-2.5 shrink-0 rounded-full bg-sa-600" aria-label="Belum dibaca"></span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @empty
        <p class="sa-white mt-6 rounded-2xl p-5 text-center text-[13px] text-sa-muted">Belum ada notifikasi di kategori ini.</p>
    @endforelse
</div>
@endsection
