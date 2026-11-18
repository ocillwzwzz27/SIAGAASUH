@extends('layouts.admin')
@section('title', 'Dashboard')

@php
    $grad = 'background:linear-gradient(135deg,#7C3AED,#C026D3)';
    $cards = [
        ['Orang Tua', number_format($stats['parents']), '👨‍👩‍👧'],
        ['Pengasuh Aktif', number_format($stats['caregivers']), '🤱'],
        ['Pesanan Bulan Ini', number_format($stats['bookings_month']), '📅'],
        ['Pendapatan (Lunas)', 'Rp ' . number_format($stats['revenue'], 0, ',', '.'), '💰'],
    ];
    $maxMonth = max(1, collect($monthly)->max('count'));
    $totalStatus = max(1, $statusCounts->sum());
    $statusLabels = ['pending' => 'Menunggu', 'accepted' => 'Diterima', 'ongoing' => 'Berjalan',
                     'completed' => 'Selesai', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan'];
@endphp

@section('content')
{{-- Kartu statistik --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($cards as $i => [$label, $value, $icon])
        <div class="rounded-2xl p-5 text-white shadow" style="{{ $grad }}">
            <div class="flex items-center justify-between">
                <p class="text-sm text-purple-100">{{ $label }}</p>
                <span class="text-2xl">{{ $icon }}</span>
            </div>
            <p class="mt-2 text-2xl font-bold">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Grafik pesanan --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm lg:col-span-2">
        <h2 class="font-semibold text-purple-800">Pesanan 6 Bulan Terakhir</h2>
        <div class="mt-4 flex h-48 items-end gap-3">
            @foreach ($monthly as $m)
                <div class="flex flex-1 flex-col items-center justify-end">
                    <span class="mb-1 text-xs font-medium text-gray-600">{{ $m['count'] }}</span>
                    <div class="w-full rounded-t-lg" style="height: {{ max(4, ($m['count'] / $maxMonth) * 140) }}px; {{ $grad }}"></div>
                    <span class="mt-2 text-xs text-gray-500">{{ $m['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Status pesanan --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <h2 class="font-semibold text-purple-800">Status Pesanan</h2>
        <div class="mt-4 space-y-3">
            @foreach ($statusLabels as $key => $label)
                @php($n = $statusCounts[$key] ?? 0)
                <div>
                    <div class="mb-1 flex justify-between text-xs text-gray-600">
                        <span>{{ $label }}</span><span>{{ $n }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100">
                        <div class="h-2 rounded-full" style="width: {{ ($n / $totalStatus) * 100 }}%; {{ $grad }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Pesanan terbaru --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm lg:col-span-2">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-semibold text-purple-800">Pesanan Terbaru</h2>
            <a href="{{ route('admin.bookings') }}" class="text-xs text-purple-700 underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-gray-400">
                    <tr><th class="py-2">Kode</th><th>Orang Tua</th><th>Pengasuh</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($recent as $b)
                        <tr>
                            <td class="py-2.5 font-mono text-xs">{{ $b->booking_code }}</td>
                            <td>{{ $b->parent->name }}</td>
                            <td>{{ $b->caregiver->user->name }}</td>
                            <td>Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                            <td>@include('admin.partials.badge', ['status' => $b->status])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Menunggu verifikasi --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-semibold text-purple-800">Menunggu Verifikasi
                @if ($pendingCount)<span class="ml-1 rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">{{ $pendingCount }}</span>@endif
            </h2>
            <a href="{{ route('admin.caregivers', ['status' => 'pending']) }}" class="text-xs text-purple-700 underline">Kelola</a>
        </div>
        <ul class="space-y-3">
            @forelse ($pending as $p)
                <li class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-purple-100 text-sm font-bold text-purple-700">
                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ $p->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $p->experience_years }} tahun pengalaman</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.caregivers.verify', $p) }}">
                        @csrf
                        <input type="hidden" name="status" value="verified">
                        <button class="rounded-lg bg-green-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-green-700">Setujui</button>
                    </form>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-gray-400">Tidak ada pengajuan baru.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
