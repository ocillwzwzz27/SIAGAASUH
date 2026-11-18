@extends('layouts.admin')
@section('title', 'Pengguna')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama atau email..."
           class="w-64 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
    <select name="role" class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm">
        <option value="">Semua peran</option>
        <option value="parent" @selected($role === 'parent')>Orang Tua</option>
        <option value="caregiver" @selected($role === 'caregiver')>Pengasuh</option>
        <option value="admin" @selected($role === 'admin')>Admin</option>
    </select>
    <button class="rounded-xl bg-purple-700 px-4 py-2 text-sm font-medium text-white">Cari</button>
</form>

<div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-400">
            <tr><th class="px-4 py-3">Nama</th><th>Email</th><th>No. HP</th><th>Peran</th><th>Kota</th><th>Terdaftar</th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($users as $u)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone ?? '-' }}</td>
                    <td>@include('admin.partials.badge', ['status' => $u->role])</td>
                    <td>{{ $u->city ?? '-' }}</td>
                    <td class="text-gray-500">{{ $u->created_at?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-10 text-center text-gray-400">Tidak ada pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
