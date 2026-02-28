@extends('layout')

@section('title', 'Профиль')

@section('content')
    <section class="profile__section">
        <div class="container profile__container">
            <h1>Мой профиль</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile__content">
                <div class="profile__menu">
                    <ul>
                        <li class="{{ request()->routeIs('profile.index') ? 'active' : '' }}">
                            <a href="{{ route('profile.index') }}">Личные данные</a>
                        </li>
                        <li class="{{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                            <a href="{{ route('profile.orders') }}">Мои заказы</a>
                        </li>
                    </ul>
                </div>

                <div class="profile__info">
                    <h2>Личные данные</h2>

                    <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                        @csrf

                        <div class="form-group">
                            <label for="name">Имя:</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Телефон:</label>
                            <input type="tel" id="phone" name="phone" value="{{ $user->phone }}" placeholder="+7 (___) ___ __-__" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}">
                        </div>

                        <div class="form-group">
                            <label for="address">Адрес для доставки:</label>
                            <input type="text" id="address" name="address" value="{{ $user->address }}">
                        </div>

                        <button type="submit" class="btn-main">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
