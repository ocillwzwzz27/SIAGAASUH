@extends('layouts.admin')
@section('title', 'Verifikasi Pengasuh')

@php
    $tabs = [null => 'Semua', 'pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
@endphp

@section('content')
<div class="mb-4 flex flex-wrap gap-2">
    @foreach ($tabs as $key => $label)
        <a href="{{ route('admin.caregivers', $key ? ['status' => $key] : []) }}"
           class="rounded-full px-4 py-1.5 text-sm {{ ($status ?? null) === $key ? 'bg-purple-700 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
            @if ($key)<span class="opacity-70">({{ $counts[$key] ?? 0 }})</span>@endif
        </a>
    @endforeach
</div>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-400">
            <tr><th class="px-4 py-3">Pengasuh</th><th>Pengalaman</th><th>Rating</th><th>Status</th><th class="pr-4 text-right">Aksi</th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($profiles as $p)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $p->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $p->user->email }}</p>
                    </td>
                    <td>{{ $p->experience_years }} tahun</td>
                    <td>★ {{ number_format($p->rating_avg, 1) }} <span class="text-gray-400">({{ $p->review_count }})</span></td>
                    <td>@include('admin.partials.badge', ['status' => $p->verification_status])</td>
                    <td class="pr-4">
                        <div class="flex justify-end gap-2">
                            @if ($p->verification_status !== 'verified')
                                <form method="POST" action="{{ route('admin.caregivers.verify', $p) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button class="rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white hover:bg-green-700">Setujui</button>
                                </form>
                            @endif
                            @if ($p->verification_status !== 'rejected')
                                <form method="POST" action="{{ route('admin.caregivers.verify', $p) }}"
                                      onsubmit="return confirm('Yakin ingin menolak / mencabut verifikasi pengasuh ini?')">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="rounded-lg bg-red-100 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-200">
                                        {{ $p->verification_status === 'verified' ? 'Cabut' : 'Tolak' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-10 text-center text-gray-400">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $profiles->links() }}</div>
@endsection
