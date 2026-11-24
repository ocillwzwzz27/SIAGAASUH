@extends('layouts.auth')
@section('title', 'Daftar Akun Baru')

@section('content')
<div class="auth-card auth-card--register">
    <div class="auth-card-header">
        <div class="auth-logo">
            <img src="{{ asset('images/logo-icon.svg') }}" alt="Logo">
        </div>
        <div class="auth-brand">Siaga<span class="auth-brand__accent">Asuh</span></div>
        <div class="auth-heading">
            <h2 class="auth-heading__title">Buat Akun Baru ✨</h2>
            <p class="auth-heading__subtitle">Bergabunglah bersama keluarga SiagaAsuh</p>
        </div>
    </div>

    <div class="auth-card-body">
        @if(isset($errors) && $errors->any())
            <div class="auth-alert">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="auth-form auth-form--compact">
            @csrf

            {{-- Pilihan Peran (gaya terpilih diatur CSS lewat :has(input:checked)) --}}
            <div>
                <label class="label-sm">Saya mendaftar sebagai</label>
                <div class="role-picker">
                    <label class="role-option">
                        <input type="radio" name="role" value="orang_tua" @checked(old('role', 'orang_tua') === 'orang_tua')>
                        <div class="role-option__emoji">👨‍👩‍👧</div>
                        <div class="role-option__name">Orang Tua</div>
                        <div class="role-option__hint">Cari Pengasuh</div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="pengasuh" @checked(old('role') === 'pengasuh')>
                        <div class="role-option__emoji">👩‍⚕️</div>
                        <div class="role-option__name">Mitra Pengasuh</div>
                        <div class="role-option__hint">Beri Layanan</div>
                    </label>
                </div>
            </div>

            <div>
                <label class="label-sm">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Siti Rahmawati">
            </div>
            <div>
                <label class="label-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
            </div>
            <div>
                <label class="label-sm">Nomor WhatsApp</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fab fa-whatsapp input-icon--whatsapp"></i></span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="08123456789">
                </div>
            </div>
            <div class="auth-grid-2">
                <div>
                    <label class="label-sm">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Min. 8 karakter">
                </div>
                <div>
                    <label class="label-sm">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi">
                </div>
            </div>

            <button type="submit" class="btn-purple">
                <i class="fas fa-user-plus btn-purple__icon"></i> Daftar Sekarang
            </button>
        </form>

        <p class="auth-footer">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk disini</a>
        </p>
    </div>
</div>
@endsection
