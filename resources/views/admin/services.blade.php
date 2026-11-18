@extends('layouts.admin')
@section('title', 'Layanan')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($services as $s)
        @php($st = $stats[$s->id] ?? null)
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-lg font-semibold text-purple-800">{{ $s->name }}</p>
            <p class="mt-1 min-h-[2.5rem] text-xs text-gray-500">{{ $s->description }}</p>
            <div class="mt-4 flex justify-between border-t pt-3 text-sm">
                <div><p class="text-xs text-gray-400">Pengasuh</p><p class="font-semibold">{{ $st->caregivers ?? 0 }}</p></div>
                <div class="text-right"><p class="text-xs text-gray-400">Rata-rata tarif</p>
                    <p class="font-semibold">Rp {{ number_format($st->avg_price ?? 0, 0, ',', '.') }}/jam</p></div>
            </div>
        </div>
    @endforeach
</div>
<p class="mt-4 text-xs text-gray-400">Tambah/ubah jenis layanan belum tersedia di versi ini.</p>
@endsection
