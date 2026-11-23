@extends('layouts.auth')

@section('title', 'Masuk ke SiagaAsuh')

@section('content')
<div style="max-width:420px;width:100%;">
    <div class="card-auth">
        {{-- Header Gradient --}}
        <div style="background:linear-gradient(135deg,#5B1D92,#7B2FBE,#9333EA);padding:32px 32px 48px;text-align:center;position:relative;">
            {{-- Logo --}}
            <div style="width:64px;height:64px;background:white;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 8px 24px rgba(0,0,0,0.2);">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <circle cx="18" cy="10" r="7" fill="#7B2FBE"/>
                    <path d="M6 32c0-6.627 5.373-12 12-12s12 5.373 12 12" stroke="#7B2FBE" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="28" cy="12" r="4" fill="#F59E0B"/>
                </svg>
            </div>
            <div style="margin-bottom:4px;">
                <span style="color:white;font-size:1.5rem;font-weight:800;letter-spacing:-0.5px;">SiagaAsuh</span>
            </div>
            <p style="color:rgba(233,213,255,0.9);font-size:0.8rem;">Pengasuh Terpercaya di Dekat Anda</p>

            {{-- Judul --}}
            <div style="margin-top:20px;">
                <h2 style="color:white;font-size:1.3rem;font-weight:700;">Selamat Datang 👋</h2>
                <p style="color:rgba(233,213,255,0.8);font-size:0.8rem;margin-top:4px;">Masuk untuk lanjutkan akses Anda</p>
            </div>

            {{-- Wave divider --}}
            <div style="position:absolute;bottom:0;left:0;right:0;line-height:0;">
                <svg viewBox="0 0 500 40" preserveAspectRatio="none" style="width:100%;height:32px;fill:white;display:block;">
                    <path d="M0,20 C120,45 380,-5 500,20 L500,40 L0,40 Z"/>
                </svg>
            </div>
        </div>

        {{-- Form --}}
        <div style="padding:12px 32px 32px;">
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:12px;font-size:0.8rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:12px;font-size:0.8rem;margin-bottom:16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" style="display:flex;flex-direction:column;gap:14px;">
                @csrf

                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Email</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.85rem;">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="email@contoh.com"
                            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 16px 11px 42px;font-size:0.875rem;outline:none;transition:all 0.2s;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#a855f7';this.style.boxShadow='0 0 0 3px rgba(168,85,247,0.15)'"
                            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    </div>
                </div>

                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <label style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Kata Sandi</label>
                        <a href="#" style="font-size:0.75rem;color:#7B2FBE;font-weight:600;text-decoration:none;">Lupa Kata Sandi?</a>
                    </div>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.85rem;">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="pwd" name="password" required placeholder="••••••••"
                            style="width:100%;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 44px 11px 42px;font-size:0.875rem;outline:none;transition:all 0.2s;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#a855f7';this.style.boxShadow='0 0 0 3px rgba(168,85,247,0.15)'"
                            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                        <button type="button" onclick="togglePwd()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                    <input type="checkbox" name="remember" id="remember" style="width:15px;height:15px;accent-color:#7B2FBE;cursor:pointer;">
                    <label for="remember" style="font-size:0.8rem;color:#6b7280;cursor:pointer;">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary" style="margin-top:4px;">
                    Masuk ke SiagaAsuh
                </button>
            </form>

            {{-- Divider --}}
            <div style="display:flex;align-items:center;gap:12px;margin:20px 0;">
                <div style="flex:1;height:1px;background:#f0e6ff;"></div>
                <span style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap;">atau masuk dengan</span>
                <div style="flex:1;height:1px;background:#f0e6ff;"></div>
            </div>

            {{-- Sosial Login (sesuai Figma) --}}
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                <button style="border:1.5px solid #f3e8ff;background:white;padding:10px;border-radius:12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#faf5ff'" onmouseout="this.style.background='white'">
                    <i class="fab fa-google" style="color:#ea4335;font-size:1rem;"></i>
                </button>
                <button style="border:1.5px solid #f3e8ff;background:white;padding:10px;border-radius:12px;cursor:pointer;" onmouseover="this.style.background='#faf5ff'" onmouseout="this.style.background='white'">
                    <i class="fab fa-apple" style="color:#1d1d1f;font-size:1rem;"></i>
                </button>
                <button style="border:1.5px solid #f3e8ff;background:white;padding:10px;border-radius:12px;cursor:pointer;" onmouseover="this.style.background='#faf5ff'" onmouseout="this.style.background='white'">
                    <i class="fab fa-facebook-f" style="color:#1877f2;font-size:1rem;"></i>
                </button>
            </div>

            {{-- Register Link --}}
            <p style="text-align:center;margin-top:20px;font-size:0.8rem;color:#9ca3af;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color:#7B2FBE;font-weight:700;text-decoration:none;">Daftar disini</a>
            </p>

            {{-- Demo Quick Login --}}
            <div style="margin-top:16px;padding:12px;background:#faf5ff;border-radius:14px;border:1px solid #e9d5ff;">
                <p style="font-size:0.65rem;font-weight:700;color:#581c87;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">💡 Demo Login Cepat:</p>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <button onclick="fill('admin@siagaasuh.com','admin123')" style="font-size:0.7rem;background:white;border:1px solid #d8b4fe;color:#7B2FBE;padding:4px 10px;border-radius:8px;cursor:pointer;font-weight:600;">🛡️ Admin</button>
                    <button onclick="fill('orangtua@siagaasuh.com','password')" style="font-size:0.7rem;background:white;border:1px solid #d8b4fe;color:#7B2FBE;padding:4px 10px;border-radius:8px;cursor:pointer;font-weight:600;">👨‍👩‍👧 Orang Tua</button>
                    <button onclick="fill('trisna@siagaasuh.com','password')" style="font-size:0.7rem;background:white;border:1px solid #d8b4fe;color:#7B2FBE;padding:4px 10px;border-radius:8px;cursor:pointer;font-weight:600;">👩‍⚕️ Pengasuh</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePwd() {
        const i = document.getElementById('pwd');
        const icon = document.getElementById('eyeIcon');
        i.type = i.type === 'password' ? 'text' : 'password';
        icon.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
    function fill(email, pass) {
        document.querySelector('[name="email"]').value = email;
        document.getElementById('pwd').value = pass;
    }
</script>
@endsection
