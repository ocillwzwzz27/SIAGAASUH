@extends('layouts.auth')
@section('title', 'Daftar Akun Baru')

@section('content')
<div class="auth-card" style="max-width:440px;width:100%;margin:20px 0;">
    <div class="auth-card-header">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:60px;height:60px;background:white;border-radius:18px;box-shadow:0 6px 20px rgba(0,0,0,0.15);margin-bottom:12px;">
            <img src="/images/logo-icon.svg" alt="Logo" style="width:38px;height:38px;">
        </div>
        <div style="color:white;font-size:1.2rem;font-weight:800;">Siaga<span style="color:#F59E0B;">Asuh</span></div>
        <div style="margin-top:16px;">
            <h2 style="color:white;font-size:1.2rem;font-weight:700;">Buat Akun Baru ✨</h2>
            <p style="color:rgba(233,213,255,0.8);font-size:0.78rem;margin-top:3px;">Bergabunglah bersama keluarga SiagaAsuh</p>
        </div>
    </div>

    <div class="auth-card-body">
        @if(isset($errors) && $errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:12px;font-size:0.8rem;margin-bottom:16px;">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" style="display:flex;flex-direction:column;gap:13px;">
            @csrf

            {{-- Pilihan Peran --}}
            <div>
                <label class="label-sm">Saya mendaftar sebagai</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px;">
                    <label style="border:2px solid #e5e7eb;border-radius:14px;padding:12px;cursor:pointer;text-align:center;transition:all 0.2s;" id="label-ortu">
                        <input type="radio" name="role" value="orang_tua" style="display:none;" checked onchange="updateRole()">
                        <div style="font-size:1.3rem;margin-bottom:4px;">👨‍👩‍👧</div>
                        <div style="font-size:0.78rem;font-weight:700;color:#374151;">Orang Tua</div>
                        <div style="font-size:0.65rem;color:#9ca3af;margin-top:1px;">Cari Pengasuh</div>
                    </label>
                    <label style="border:2px solid #e5e7eb;border-radius:14px;padding:12px;cursor:pointer;text-align:center;transition:all 0.2s;" id="label-pgsh">
                        <input type="radio" name="role" value="pengasuh" style="display:none;" onchange="updateRole()">
                        <div style="font-size:1.3rem;margin-bottom:4px;">👩‍⚕️</div>
                        <div style="font-size:0.78rem;font-weight:700;color:#374151;">Mitra Pengasuh</div>
                        <div style="font-size:0.65rem;color:#9ca3af;margin-top:1px;">Beri Layanan</div>
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
                    <span class="input-icon"><i class="fab fa-whatsapp" style="color:#25d366;"></i></span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="08123456789">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <label class="label-sm">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Min. 8 karakter">
                </div>
                <div>
                    <label class="label-sm">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi">
                </div>
            </div>

            <button type="submit" class="btn-purple" style="margin-top:4px;">
                <i class="fas fa-user-plus" style="margin-right:8px;"></i> Daftar Sekarang
            </button>
        </form>

        <p style="text-align:center;margin-top:16px;font-size:0.8rem;color:#9ca3af;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#7B2FBE;font-weight:700;text-decoration:none;">Masuk disini</a>
        </p>
    </div>
</div>

<script>
function updateRole() {
    const ortu = document.querySelector('[value="orang_tua"]').checked;
    document.getElementById('label-ortu').style.borderColor = ortu ? '#7B2FBE' : '#e5e7eb';
    document.getElementById('label-ortu').style.background = ortu ? '#faf5ff' : 'white';
    document.getElementById('label-pgsh').style.borderColor = !ortu ? '#7B2FBE' : '#e5e7eb';
    document.getElementById('label-pgsh').style.background = !ortu ? '#faf5ff' : 'white';
}
// Set default style
document.getElementById('label-ortu').style.borderColor = '#7B2FBE';
document.getElementById('label-ortu').style.background = '#faf5ff';
document.querySelectorAll('[name="role"]').forEach(r => r.addEventListener('change', updateRole));
</script>
@endsection
