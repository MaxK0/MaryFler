@extends('layout')

@section('title', 'Регистрация')

@section('content')
    <section class="auth__section">
        <div class="container auth__container">
            <div class="auth-form-wrapper">
                <h1>Регистрация</h1>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Имя*:</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон*:</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (___) ___ __-__" required>
                        @error('phone')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес для доставки:</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}">
                        @error('address')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль*:</label>
                        <input type="password" id="password" name="password" required>
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Подтверждение пароля*:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-main">Зарегистрироваться</button>
                </form>

                <div class="auth-links">
                    <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
