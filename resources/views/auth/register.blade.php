@extends('layouts.auth')
@section('title', 'Daftar Akun Baru')

@section('content')
<div class="auth-card auth-card--register">
    <div class="auth-card-header">
        <div class="auth-logo">
            <img src="{{ asset('images/logo-card.png') }}" alt="Logo SiagaAsuh">
        </div>
        <div class="auth-brand">Siaga<span class="auth-brand__accent">Asuh</span></div>
        <div class="auth-heading">
            <h2 class="auth-heading__title">Buat Akun Baru ✨</h2>
            <p class="auth-heading__subtitle">Bergabunglah bersama keluarga PT SiagaAsuh</p>
        </div>
    </div>

    <div class="auth-card-body">
        @if(isset($errors) && $errors->any())
            <div class="auth-alert">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" class="auth-form auth-form--compact">
            @csrf

            {{-- Pilihan Peran --}}
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
                        <div class="role-option__hint">Gabung PT SiagaAsuh</div>
                    </label>
                </div>
            </div>

            <div>
                <label class="label-sm">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Siti Rahmawati">
            </div>
            <div>
                <label class="label-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
            </div>
            <div>
                <label class="label-sm">Nomor WhatsApp Aktif</label>
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

            {{-- Bagian Khusus Pendaftaran Mitra PT SiagaAsuh --}}
            <div id="mitra-onboarding" class="mitra-section {{ old('role') === 'pengasuh' ? '' : 'is-hidden' }}">
                <div class="mitra-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Calon Mitra PT SiagaAsuh wajib melengkapi berkas &amp; menyetujui S&amp;K untuk proses verifikasi admin. Tarif layanan akan ditentukan oleh Admin PT.</span>
                </div>

                <div>
                    <label class="label-sm">Nomor Induk Kependudukan (NIK - 16 Digit)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="Contoh: 3319012345678901" data-mitra-required>
                </div>

                <div class="auth-grid-2">
                    <div>
                        <label class="label-sm">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" data-mitra-required>
                            <option value="">-- Pilih --</option>
                            @foreach(['SMP / Sederajat', 'SMA / SMK', 'D3 Kebidanan / Keperawatan', 'S1 PAUD / Psikologi / Kesehatan', 'Lainnya'] as $pend)
                                <option value="{{ $pend }}" @selected(old('pendidikan_terakhir') === $pend)>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label-sm">Kontak Darurat Keluarga</label>
                        <input type="text" name="kontak_darurat" value="{{ old('kontak_darurat') }}" placeholder="Ibu / 0812xxx" data-mitra-required>
                    </div>
                </div>

                <div>
                    <label class="label-sm">Alamat Domisili Lengkap</label>
                    <textarea name="alamat_domisili" rows="2" placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kota/Kabupaten" data-mitra-required>{{ old('alamat_domisili') }}</textarea>
                </div>

                <div>
                    <label class="label-sm">Kompetensi &amp; Keahlian Pengasuhan</label>
                    <textarea name="kompetensi" rows="2" placeholder="Contoh: Perawatan bayi baru lahir, P3K/First Aid, MPASI, pendampingan balita..." data-mitra-required>{{ old('kompetensi') }}</textarea>
                </div>

                <div>
                    <label class="label-sm">Pengalaman Mengasuh &amp; Latar Belakang Hidup</label>
                    <textarea name="pengalaman" rows="3" placeholder="Ceritakan pengalaman merawat anak, lama bekerja, serta motivasi menjadi pengasuh profesional (min. 20 karakter)..." data-mitra-required>{{ old('pengalaman') }}</textarea>
                </div>

                {{-- Upload Berkas Wajib --}}
                <div>
                    <label class="label-sm">1. Foto KTP (Wajib)</label>
                    <input type="file" name="foto_identitas" accept=".jpg,.jpeg,.png,.pdf" class="file-input" data-mitra-required>
                    <span class="file-hint">Format JPG, PNG, atau PDF maks. 3 MB</span>
                </div>

                <div>
                    <label class="label-sm">2. SKCK Aktif (Wajib)</label>
                    <input type="file" name="dokumen_skck" accept=".jpg,.jpeg,.png,.pdf" class="file-input" data-mitra-required>
                    <span class="file-hint">Surat Keterangan Catatan Kepolisian yang masih berlaku (maks. 3 MB)</span>
                </div>

                <div>
                    <label class="label-sm">3. Surat Keterangan Sehat (Wajib)</label>
                    <input type="file" name="dokumen_surat_sehat" accept=".jpg,.jpeg,.png,.pdf" class="file-input" data-mitra-required>
                    <span class="file-hint">Dari Puskesmas / Klinik / RS bebas penyakit menular (maks. 3 MB)</span>
                </div>

                <div>
                    <label class="label-sm">4. Surat Pengalaman / Sertifikat Mengasuh Anak (Wajib)</label>
                    <input type="file" name="dokumen_surat_pengalaman" accept=".jpg,.jpeg,.png,.pdf" class="file-input" data-mitra-required>
                    <span class="file-hint">Surat keterangan kerja pengasuh, sertifikat pelatihan, atau PAUD (maks. 3 MB)</span>
                </div>

                {{-- Syarat & Ketentuan PT SiagaAsuh --}}
                <div>
                    <label class="label-sm">Syarat &amp; Ketentuan Mitra PT SiagaAsuh</label>
                    <div class="sk-box">
                        <h4>Perjanjian Kemitraan Pengasuh Profesional PT SiagaAsuh</h4>
                        <ol>
                            <li><strong>Keaslian Dokumen:</strong> Seluruh data identitas (KTP), SKCK, Surat Sehat, dan Surat Pengalaman yang diunggah adalah benar dan asli. Pemalsuan dokumen berakibat penolakan permanen dan proses hukum.</li>
                            <li><strong>Keselamatan &amp; Etika Anak:</strong> Mitra wajib menjaga keselamatan, kenyamanan, dan martabat anak tanpa segala bentuk kekerasan fisik, verbal, maupun kelalaian.</li>
                            <li><strong>Pemantauan Wearable IoT:</strong> Mitra bersedia menggunakan &amp; memastikan perangkat wearable anak terpasang selama sesi pengasuhan aktif berlangsung.</li>
                            <li><strong>Kerahasiaan Keluarga:</strong> Mitra dilarang menyebarluaskan foto, alamat, atau privasi keluarga pengguna layanan ke pihak mana pun.</li>
                            <li><strong>Penetapan Tarif &amp; Evaluasi:</strong> Tarif layanan ditetapkan oleh Admin PT SiagaAsuh berdasarkan zona/jarak kota dan standar perusahaan. PT berhak menangguhkan akun apabila terjadi pelanggaran SOP.</li>
                        </ol>
                    </div>

                    <label class="sk-agree">
                        <input type="checkbox" name="setuju_sk" value="1" @checked(old('setuju_sk')) data-mitra-required>
                        <span>Saya telah membaca, memahami, dan menyetujui seluruh <strong>Syarat &amp; Ketentuan Mitra PT SiagaAsuh</strong> serta bersedia mengikuti proses verifikasi.</span>
                    </label>
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

@push('scripts')
<script>
    (function () {
        const roleRadios = document.querySelectorAll('input[name="role"]');
        const mitraBox = document.getElementById('mitra-onboarding');
        const mitraFields = mitraBox.querySelectorAll('[data-mitra-required]');

        function syncMitraFields() {
            const selected = document.querySelector('input[name="role"]:checked')?.value;
            const isMitra = selected === 'pengasuh';
            mitraBox.classList.toggle('is-hidden', !isMitra);
            mitraFields.forEach((el) => {
                el.required = isMitra;
                el.disabled = !isMitra;
            });
        }

        roleRadios.forEach((radio) => radio.addEventListener('change', syncMitraFields));
        syncMitraFields();
    })();
</script>
@endpush
