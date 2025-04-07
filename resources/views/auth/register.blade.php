@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Kayıt Ol</h2>
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name">Adınız</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

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

            <div class="mb-3">
                <label for="password_confirmation">Şifre Tekrar</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Kayıt Ol</button>
        </form>
    </div>
@endsection
