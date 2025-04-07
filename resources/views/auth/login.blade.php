@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Giriş Yap</h2>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email">E-Posta</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password">Şifre</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Giriş Yap</button>
        </form>
    </div>
@endsection
