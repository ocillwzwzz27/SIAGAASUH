@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="w-full max-w-md my-6">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="gradient-purple px-8 pt-8 pb-10 text-center relative">
            <h1 class="text-2xl font-bold text-white tracking-wide">Daftar Akun</h1>
            <p class="text-purple-100 text-sm mt-1">Bergabung bersama keluarga SiagaAsuh</p>

            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 500 40" preserveAspectRatio="none" class="w-full h-8 text-white fill-current">
                    <path d="M0,20 C150,45 350,-5 500,20 L500,40 L0,40 Z"></path>
                </svg>
            </div>
        </div>

        <div class="p-8 pt-2">
            @if(isset($errors) && $errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5">
                @csrf

                {{-- Pilihan Peran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Saya mendaftar sebagai:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex flex-col items-center p-3 border-2 border-purple-200 rounded-2xl cursor-pointer hover:border-purple-500 transition has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50">
                            <input type="radio" name="role" value="orang_tua" class="sr-only" checked>
                            <i class="fas fa-home text-purple-600 text-xl mb-1"></i>
                            <span class="text-xs font-bold text-gray-800">Orang Tua</span>
                            <span class="text-[10px] text-gray-500">Cari Pengasuh</span>
                        </label>
                        <label class="relative flex flex-col items-center p-3 border-2 border-purple-200 rounded-2xl cursor-pointer hover:border-purple-500 transition has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50">
                            <input type="radio" name="role" value="pengasuh" class="sr-only" {{ old('role') === 'pengasuh' ? 'checked' : '' }}>
                            <i class="fas fa-user-nurse text-purple-600 text-xl mb-1"></i>
                            <span class="text-xs font-bold text-gray-800">Mitra Pengasuh</span>
                            <span class="text-[10px] text-gray-500">Beri Layanan</span>
                        </label>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Siti Rahma" class="input text-sm">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="input text-sm">
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Handphone / WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="08123456789" class="input text-sm">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="input text-sm">
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="input text-sm">
                </div>

                {{-- Tombol Daftar --}}
                <div class="pt-3">
                    <button type="submit" class="w-full btn-primary text-white py-3 rounded-2xl font-semibold shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition duration-200">
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="text-center mt-5 text-xs text-gray-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-purple-600 font-bold hover:underline">Masuk disini</a>
            </div>
        </div>
    </div>
</div>
@endsection
