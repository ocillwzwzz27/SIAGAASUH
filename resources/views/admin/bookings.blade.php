@extends('layouts.admin')
@section('title', 'Pesanan')

@php
    $tabs = [null => 'Semua', 'pending' => 'Menunggu', 'accepted' => 'Diterima', 'ongoing' => 'Berjalan',
             'completed' => 'Selesai', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan'];
@endphp

@section('content')
<div class="mb-4 flex flex-wrap gap-2">
    @foreach ($tabs as $key => $label)
        <a href="{{ route('admin.bookings', $key ? ['status' => $key] : []) }}"
           class="rounded-full px-4 py-1.5 text-sm {{ ($status ?? null) === $key ? 'bg-purple-700 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">{{ $label }}</a>
    @endforeach
</div>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-400">
            <tr><th class="px-4 py-3">Kode</th><th>Orang Tua</th><th>Pengasuh</th><th>Layanan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($bookings as $b)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $b->booking_code }}</td>
                    <td>{{ $b->parent->name }}</td>
                    <td>{{ $b->caregiver->user->name }}</td>
                    <td>{{ $b->serviceType->name }}</td>
                    <td class="whitespace-nowrap">{{ $b->service_date->format('d/m/Y') }}<br>
                        <span class="text-xs text-gray-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</span></td>
                    <td class="whitespace-nowrap">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                    <td>@include('admin.partials.badge', ['status' => $b->status])</td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-10 text-center text-gray-400">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
