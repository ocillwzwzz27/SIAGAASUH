<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CaregiverProfile;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(auth()->user());
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:orang_tua,pengasuh'],
        ];

        if ($request->role === 'pengasuh') {
            $rules = array_merge($rules, [
                'nik' => ['required', 'digits:16'],
                'alamat_domisili' => ['required', 'string', 'max:500'],
                'pendidikan_terakhir' => ['required', 'string', 'max:100'],
                'kontak_darurat' => ['required', 'string', 'max:150'],
                'kompetensi' => ['required', 'string', 'max:1000'],
                'pengalaman' => ['required', 'string', 'min:20', 'max:2000'],
                'foto_identitas' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
                'dokumen_skck' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
                'dokumen_surat_sehat' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
                'dokumen_surat_pengalaman' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
                'setuju_sk' => ['accepted'],
            ]);
        }

        $messages = [
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'pengalaman.min' => 'Ceritakan pengalaman mengasuh & latar belakang Anda minimal 20 karakter.',
            'setuju_sk.accepted' => 'Anda wajib membaca dan menyetujui Syarat & Ketentuan Mitra PT SiagaAsuh.',
        ];

        $request->validate($rules, $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'pengasuh') {
            CaregiverProfile::create([
                'user_id' => $user->id,
                'nik' => $request->nik,
                'alamat_domisili' => $request->alamat_domisili,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kontak_darurat' => $request->kontak_darurat,
                'kompetensi' => $request->kompetensi,
                'pengalaman' => $request->pengalaman,
                'foto_identitas' => $request->file('foto_identitas')->store('identitas', 'public'),
                'dokumen_skck' => $request->file('dokumen_skck')->store('skck', 'public'),
                'dokumen_surat_sehat' => $request->file('dokumen_surat_sehat')->store('surat-sehat', 'public'),
                'dokumen_surat_pengalaman' => $request->file('dokumen_surat_pengalaman')->store('surat-pengalaman', 'public'),
                'setuju_sk' => true,
                'sk_accepted_at' => now(),
                'status_verifikasi' => 'menunggu',
            ]);

            // Beri tahu Admin PT ada pendaftar Mitra Pengasuh baru
            User::where('role', 'admin')->each(function (User $admin) use ($user) {
                AppNotification::kirim(
                    $admin->id,
                    '🆕 Pendaftar Mitra Pengasuh Baru',
                    $user->name . ' baru saja mendaftar sebagai Mitra Pengasuh PT SiagaAsuh dan menunggu verifikasi berkas.',
                    'sistem'
                );
            });
        }

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'orang_tua' => redirect()->route('parent.dashboard'),
            'pengasuh' => redirect()->route('caregiver.dashboard'),
            default => redirect('/'),
        };
    }
}
