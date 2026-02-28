@extends('layout')

@section('title', 'Вход')

@section('content')
    <section class="auth__section">
        <div class="container auth__container">
            <div class="auth-form-wrapper">
                <h1>Вход в аккаунт</h1>

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="phone">Телефон:</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (___) ___ __-__" required>
                        @error('phone')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль:</label>
                        <input type="password" id="password" name="password" required>
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-main">Войти</button>
                </form>

                <div class="auth-links">
                    <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
