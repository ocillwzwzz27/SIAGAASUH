@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Daftar semua pengguna terdaftar dalam sistem SiagaAsuh')

@section('content')
<div class="card space-y-4">
    {{-- Filter & Search Form --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengguna..." class="input text-sm">
        </div>
        <div>
            <select name="role" class="input text-sm py-2.5">
                <option value="">Semua Peran</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="orang_tua" {{ request('role') === 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                <option value="pengasuh" {{ request('role') === 'pengasuh' ? 'selected' : '' }}>Pengasuh</option>
            </select>
        </div>
        <button type="submit" class="btn-primary text-sm py-2.5 px-4 flex items-center gap-2">
            <i class="fas fa-search"></i> Filter
        </button>
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-red-500 underline">Reset</a>
        @endif
    </form>

    {{-- Users Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                    <th class="py-3 px-4">Pengguna</th>
                    <th class="py-3 px-4">Kontak</th>
                    <th class="py-3 px-4">Peran (Role)</th>
                    <th class="py-3 px-4">Terdaftar</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($users as $u)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-gray-800">{{ $u->name }}</div>
                            <div class="text-xs text-gray-400">{{ $u->email }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-gray-600">
                            {{ $u->phone ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                @if($u->role === 'admin') bg-purple-100 text-siaga
                                @elseif($u->role === 'orang_tua') bg-blue-100 text-blue-700
                                @else bg-teal-100 text-teal-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-gray-500">
                            {{ $u->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus akun ini?" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm p-1.5 rounded-lg hover:bg-red-50 transition" title="Hapus Akun">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
