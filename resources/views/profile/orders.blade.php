@extends('layout')

@section('title', 'Мои заказы')

@section('content')
    <section class="profile__section">
        <div class="container profile__container">
            <h1>Мои заказы</h1>

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

                <div class="profile__orders">
                    <div class="orders-filter">
                        <button class="filter-btn active" data-filter="all">Все</button>
                        <button class="filter-btn" data-filter="new">Новые</button>
                        <button class="filter-btn" data-filter="in_progress">В работе</button>
                        <button class="filter-btn" data-filter="ready">Готовы к получению</button>
                        <button class="filter-btn" data-filter="completed">Полученные</button>
                        <button class="filter-btn" data-filter="cancelled">Отмененные</button>
                    </div>

                    <div class="orders-list">
                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                                <div class="order-card" data-status="{{ $order->status }}">
                                    <div class="order-header">
                                        <div class="order-number">Заказ #{{ $order->id }}</div>
                                        <div class="order-date">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                                        <div class="order-status {{ $order->status }}">{{ $order->status }}</div>
                                    </div>

                                    <div class="order-items">
                                        @foreach($order->items as $item)
                                            <div class="order-item">
                                                <div class="item-info">
                                                    <div class="item-name">{{ $item->productVariation->product->name }}</div>
                                                    @if($item->productVariation->name)
                                                        <div class="item-variation">{{ $item->productVariation->name }}</div>
                                                    @endif
                                                    <div class="item-price">{{ number_format($item->price, 2) }} ₽ × {{ $item->quantity }}</div>
                                                </div>
                                                <div class="item-total">
                                                    {{ number_format($item->price * $item->quantity, 2) }} ₽
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="order-footer">
                                        <div class="order-total">
                                            <span>Итого:</span>
                                            <span>{{ number_format($order->total_price, 2) }} ₽</span>
                                        </div>

                                        <div class="order-delivery">
                                            @if($order->delivery_type === 'pickup')
                                                <span>Самовывоз</span>
                                            @else
                                                <span>Доставка: {{ $order->delivery_address }}</span>
                                            @endif
                                        </div>

                                        <div class="order-actions">
                                            <a href="{{ route('profile.order', $order->id) }}" class="btn-secondary">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{ $orders->links() }}
                        @else
                            <div class="no-orders">
                                <p>У вас пока нет заказов</p>
                                <a href="{{ route('products.index') }}" class="btn-main">Перейти к покупкам</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Фильтрация заказов по статусу
            const filterBtns = document.querySelectorAll('.filter-btn');
            const orderCards = document.querySelectorAll('.order-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Убираем активный класс у всех кнопок
                    filterBtns.forEach(el => el.classList.remove('active'));
                    // Добавляем активный класс текущей кнопке
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    orderCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const status = card.getAttribute('data-status');

                            if (filter === 'new' && status === 'Новое') {
                                card.style.display = 'block';
                            } else if (filter === 'in_progress' && status === 'В работе') {
                                card.style.display = 'block';
                            } else if (filter === 'ready' && status === 'Готово к получению') {
                                card.style.display = 'block';
                            } else if (filter === 'completed' && status === 'Получено') {
                                card.style.display = 'block';
                            } else if (filter === 'cancelled' && status === 'Отменено') {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
@endpush
