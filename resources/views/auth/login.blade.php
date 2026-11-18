@extends('layouts.auth')

@section('title', 'Masuk')
@section('heading', 'Selamat Datang')
@section('subheading', 'Masuk untuk memesan pengasuh terpercaya.')

@section('content')
<form method="POST" action="{{ route('login.attempt') }}" novalidate>
    @csrf

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" autofocus class="{{ $errors->has('email') ? 'has-error' : '' }}">
        @error('email') <span class="err">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password">Kata Sandi</label>
        <input id="password" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" class="{{ $errors->has('password') ? 'has-error' : '' }}">
        @error('password') <span class="err">{{ $message }}</span> @enderror
    </div>

    <label class="remember">
        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
        <span>Ingat saya</span>
    </label>

    <button type="submit" class="btn-main">Masuk</button>

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</form>
@endsection
