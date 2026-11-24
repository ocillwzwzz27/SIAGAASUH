@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div class="auth-card">
    {{-- Header --}}
    <div class="auth-card-header">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:68px;height:68px;background:white;border-radius:20px;box-shadow:0 8px 24px rgba(0,0,0,0.18);margin-bottom:14px;">
            <img src="/images/logo-icon.svg" alt="Logo" style="width:44px;height:44px;">
        </div>
        <div style="color:white;font-size:1.3rem;font-weight:800;letter-spacing:-0.3px;">
            Siaga<span style="color:#F59E0B;">Asuh</span>
        </div>
        <p style="color:rgba(233,213,255,0.85);font-size:0.78rem;margin-top:4px;">Pengasuh Terpercaya Kapan Saja</p>
        <div style="margin-top:18px;">
            <h2 style="color:white;font-size:1.25rem;font-weight:700;">Selamat Datang 👋</h2>
            <p style="color:rgba(233,213,255,0.8);font-size:0.8rem;margin-top:3px;">Masuk untuk melanjutkan ke akun Anda</p>
        </div>
    </div>

    {{-- Body --}}
    <div class="auth-card-body">
        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:12px;font-size:0.8rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:12px;font-size:0.8rem;margin-bottom:16px;">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" style="display:flex;flex-direction:column;gap:14px;">
            @csrf
            <div>
                <label class="label-sm">Email</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <label class="label-sm" style="margin:0;">Kata Sandi</label>
                    <a href="#" style="font-size:0.72rem;color:#7B2FBE;font-weight:600;text-decoration:none;">Lupa kata sandi?</a>
                </div>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="pwd" name="password" required placeholder="••••••••"
                        style="padding-left:42px;padding-right:44px;">
                    <button type="button" onclick="togglePwd()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;font-size:0.85rem;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="remember" id="rem" style="width:15px;height:15px;accent-color:#7B2FBE;cursor:pointer;flex-shrink:0;">
                <label for="rem" style="font-size:0.8rem;color:#6b7280;cursor:pointer;">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn-purple" style="margin-top:4px;">
                <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i> Masuk ke SiagaAsuh
            </button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">atau lanjut dengan</span>
            <div class="divider-line"></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <button class="social-btn"><i class="fab fa-google" style="color:#ea4335;"></i></button>
            <button class="social-btn"><i class="fab fa-apple" style="color:#1d1d1f;"></i></button>
            <button class="social-btn"><i class="fab fa-facebook-f" style="color:#1877f2;"></i></button>
        </div>

        <p style="text-align:center;margin-top:18px;font-size:0.8rem;color:#9ca3af;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#7B2FBE;font-weight:700;text-decoration:none;">Daftar disini</a>
        </p>

        <div class="demo-box">
            <p style="font-size:0.65rem;font-weight:700;color:#581c87;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px;">💡 Demo Login Cepat</p>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                <button class="demo-btn" onclick="fill('admin@siagaasuh.com','admin123')">🛡️ Admin</button>
                <button class="demo-btn" onclick="fill('orangtua@siagaasuh.com','password')">👨‍👩‍👧 Orang Tua</button>
                <button class="demo-btn" onclick="fill('trisna@siagaasuh.com','password')">👩‍⚕️ Pengasuh</button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePwd() {
        const i = document.getElementById('pwd');
        const ic = document.getElementById('eyeIcon');
        i.type = i.type === 'password' ? 'text' : 'password';
        ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
    function fill(email, pass) {
        document.querySelector('[name="email"]').value = email;
        document.getElementById('pwd').value = pass;
    }
</script>
@endsection
