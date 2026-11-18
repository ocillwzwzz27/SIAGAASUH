@extends('layouts.admin')
@section('title', 'Pembayaran')

@section('content')
<div class="mb-4 grid gap-4 sm:grid-cols-2">
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Total Lunas</p>
        <p class="mt-1 text-2xl font-bold text-green-700">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Menunggu Pembayaran</p>
        <p class="mt-1 text-2xl font-bold text-yellow-600">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
    </div>
</div>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-400">
            <tr><th class="px-4 py-3">ID Transaksi</th><th>Pesanan</th><th>Metode</th><th>Jumlah</th><th>Status</th><th>Dibayar</th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($payments as $p)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $p->transaction_id ?? '-' }}</td>
                    <td class="font-mono text-xs">{{ $p->booking->booking_code }}</td>
                    <td>{{ $p->method ?? '-' }}</td>
                    <td class="whitespace-nowrap">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td>@include('admin.partials.badge', ['status' => $p->status])</td>
                    <td class="text-gray-500">{{ $p->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-10 text-center text-gray-400">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->links() }}</div>
@endsection
