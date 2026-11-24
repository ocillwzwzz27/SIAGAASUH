# Struktur Frontend SiagaAsuh

Aturan: **Blade = HTML saja**. CSS di `resources/css`, JS di `resources/js`.
Tidak ada `<style>`, atribut `style=""`, `<script>` inline, maupun `onclick=""` di view.

```
resources/
├── css/
│   ├── app.css                 # entry dashboard (Tailwind + daftar import)
│   ├── auth.css                # entry login/register (tanpa Tailwind)
│   ├── base/                   # tokens (warna/font), theme Tailwind, reset
│   ├── components/             # card, button, badge, form, sidebar, topbar, empty-state
│   ├── pages/                  # admin-dashboard, notifications, monitor
│   └── auth/                   # base, layout, form, login, register
├── js/
│   ├── app.js                  # entry global
│   ├── bootstrap.js
│   ├── components/confirm.js   # data-confirm="..." untuk konfirmasi hapus/batal
│   └── pages/                  # monitor, admin-caregiver-show, auth/login
└── views/
    ├── layouts/                # app, auth, admin, parent, caregiver
    │   └── partials/           # assets, sidebar, topbar, flash, auth-hero
    └── admin|parent|caregiver|auth/...
```

## vite.config.js
Pastikan `input` memuat semua entry:

```js
laravel({
    input: [
        'resources/css/app.css',
        'resources/css/auth.css',
        'resources/js/app.js',
        'resources/js/pages/monitor.js',
        'resources/js/pages/admin-caregiver-show.js',
        'resources/js/pages/auth/login.js',
    ],
    refresh: true,
}),
```
Lalu jalankan `npm run dev` (atau `npm run build` untuk produksi).

## Konvensi
- Warna/font hanya diubah di `css/base/tokens.css`.
- Halaman baru: buat `css/pages/nama.css` + `@import` di `app.css`; JS-nya di `js/pages/nama.js`
  dan dipanggil lewat `@push('scripts') @vite('...') @endpush`.
- Data dari PHP ke JS lewat atribut `data-*` (lihat `#monitor` di halaman monitor).
