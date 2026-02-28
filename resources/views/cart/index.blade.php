@extends('layout')

@section('title', 'Корзина')

@section('content')
    <section class="cart__section">
        <div class="container cart__container">
            <h1>Корзина</h1>

            @if(count($cart) > 0)
                <div class="cart__content">
                    <div class="cart__items">
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            @foreach($cart as $variationId => $item)
                                <div class="cart-item">
                                    <div class="cart-item__image">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                        @endif
                                    </div>

                                    <div class="cart-item__info">
                                        <h3>{{ $item['name'] }}</h3>
                                        @if($item['variation_name'])
                                            <p class="variation-name">{{ $item['variation_name'] }}</p>
                                        @endif
                                        <div class="cart-item__price">
                                            {{ number_format($item['price'], 2) }} ₽
                                        </div>
                                    </div>

                                    <div class="cart-item__quantity">
                                        <div class="quantity-selector">
                                            <button type="button" class="quantity-btn minus" data-variation-id="{{ $variationId }}">-</button>
                                            <input type="number" name="quantities[{{ $variationId }}]" value="{{ $item['quantity'] }}" min="1" class="quantity-input">
                                            <button type="button" class="quantity-btn plus" data-variation-id="{{ $variationId }}">+</button>
                                        </div>
                                    </div>

                                    <div class="cart-item__total">
                                        {{ number_format($item['price'] * $item['quantity'], 2) }} ₽
                                    </div>

                                    <div class="cart-item__remove">
                                        <a href="{{ route('cart.remove', $variationId) }}" class="remove-btn">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            <div class="cart__actions">
                                <button type="submit" class="btn-main">Обновить корзину</button>
                                <a href="{{ route('home') }}" class="btn-secondary">Продолжить покупки</a>
                            </div>
                        </form>
                    </div>

                    <div class="cart__summary">
                        <div class="cart-summary__item">
                            <span>Товаров:</span>
                            <span>{{ count($cart) }}</span>
                        </div>
                        <div class="cart-summary__item">
                            <span>Итого:</span>
                            <span class="total-price">{{ number_format($totalPrice, 2) }} ₽</span>
                        </div>

                        <div class="cart__checkout">
                            <h3>Оформление заказа</h3>
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Способ получения:</label>
                                    <div class="delivery-options">
                                        <label class="delivery-option">
                                            <input type="radio" name="delivery_type" value="pickup" checked>
                                            <span>Самовывоз</span>
                                        </label>
                                        <label class="delivery-option">
                                            <input type="radio" name="delivery_type" value="delivery">
                                            <span>Доставка</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group delivery-address-group" style="display: none;">
                                    <label for="delivery_address">Адрес доставки:</label>
                                    <input type="text" id="delivery_address" name="delivery_address" value="{{ auth()->user()->address ?? '' }}">
                                </div>

                                <button type="submit" class="btn-main">Заказать</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <p>Ваша корзина пуста</p>
                    <a href="{{ route('products.index') }}" class="btn-main">Перейти к покупкам</a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка изменения количества товаров в корзине
            const minusBtns = document.querySelectorAll('.quantity-btn.minus');
            const plusBtns = document.querySelectorAll('.quantity-btn.plus');

            minusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const variationId = this.getAttribute('data-variation-id');
                    const input = document.querySelector(`input[name="quantities[${variationId}]"]`);
                    let currentValue = parseInt(input.value);

                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                    }
                });
            });

            plusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const variationId = this.getAttribute('data-variation-id');
                    const input = document.querySelector(`input[name="quantities[${variationId}]"]`);
                    let currentValue = parseInt(input.value);

                    input.value = currentValue + 1;
                });
            });

            // Обработка выбора способа получения
            const deliveryOptions = document.querySelectorAll('.delivery-option input');
            const deliveryAddressGroup = document.querySelector('.delivery-address-group');

            deliveryOptions.forEach(option => {
                option.addEventListener('change', function() {
                    if (this.value === 'delivery') {
                        deliveryAddressGroup.style.display = 'block';
                    } else {
                        deliveryAddressGroup.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpush
