@extends('layouts.auth')
@section('title', 'Masuk')

@push('scripts')
    @vite('resources/js/pages/auth/login.js')
@endpush

@section('content')
<div class="auth-card auth-login">
    {{-- Background desain (satu SVG utuh) --}}
    <img class="auth-login__bg" src="{{ asset('images/auth/login-page.svg') }}" alt="" aria-hidden="true">

    {{-- Header --}}
    <div class="auth-card-header">
        <div class="auth-logo">
            <img src="{{ asset('images/logo-card.png') }}" alt="Logo SiagaAsuh">
        </div>
        <div class="auth-heading">
            <h2 class="auth-heading__title">Selamat Datang</h2>
            <p class="auth-heading__subtitle">Masuk untuk lanjut</p>
        </div>
    </div>

    {{-- Body --}}
    <div class="auth-card-body">
        @if(session('error'))
            <div class="auth-alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="auth-alert">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="auth-form">
            @csrf
            <div class="login-field">
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email atau No. Handphone">
                </div>
            </div>
            <div class="login-field login-field--password">
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-key"></i></span>
                    <input type="password" id="pwd" name="password" required placeholder="••••••••">
                    <button type="button" id="togglePassword" class="input-toggle" aria-label="Tampilkan kata sandi">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="login-forgot"><a href="#">Lupa Kata Sandi?</a></div>

            <div hidden>
                <input type="checkbox" name="remember" id="rem" class="auth-remember-box">
                <label for="rem" class="auth-remember-label">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn-purple login-submit">Masuk</button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">atau lanjut dengan</span>
            <div class="divider-line"></div>
        </div>

        <div class="social-grid">
            <button type="button" class="social-btn" aria-label="Lanjut dengan Google">
                <img src="{{ asset('images/auth/social/google.svg') }}" alt="" class="social-btn__img">
            </button>
            <button type="button" class="social-btn" aria-label="Lanjut dengan Apple">
                <img src="{{ asset('images/auth/social/apple.svg') }}" alt="" class="social-btn__img">
            </button>
            <button type="button" class="social-btn" aria-label="Lanjut dengan Facebook">
                <img src="{{ asset('images/auth/social/facebook.svg') }}" alt="" class="social-btn__img">
            </button>
        </div>

        <p class="auth-footer">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar disini</a>
        </p>

        <div class="demo-box">
            <p class="demo-box__title">💡 Demo Login Cepat</p>
            <div class="demo-box__actions">
                <button type="button" class="demo-btn" data-demo-email="admin@siagaasuh.com" data-demo-password="admin123">🛡️ Admin</button>
                <button type="button" class="demo-btn" data-demo-email="orangtua@siagaasuh.com" data-demo-password="password">👨‍👩‍👧 Orang Tua</button>
                <button type="button" class="demo-btn" data-demo-email="trisna@siagaasuh.com" data-demo-password="password">👩‍⚕️ Pengasuh</button>
            </div>
        </div>
    </div>
</div>
@endsection
