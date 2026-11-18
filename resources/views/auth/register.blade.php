@extends('layouts.auth')

@section('title', 'Daftar')
@section('heading', 'Buat Akun')
@section('subheading', 'Daftar gratis dan temukan pengasuh terdekat.')

@section('content')
<form method="POST" action="{{ route('register.store') }}" novalidate>
    @csrf

    <div class="field">
        <label for="name">Nama Lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" autocomplete="name" autofocus class="{{ $errors->has('name') ? 'has-error' : '' }}">
        @error('name') <span class="err">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" class="{{ $errors->has('email') ? 'has-error' : '' }}">
        @error('email') <span class="err">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password">Kata Sandi</label>
        <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" class="{{ $errors->has('password') ? 'has-error' : '' }}">
        @error('password') <span class="err">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" autocomplete="new-password">
    </div>

    <button type="submit" class="btn-main">Daftar</button>

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</form>
@endsection
