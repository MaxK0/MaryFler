@extends('layout')

@section('title', 'Корзина')

@section('content')
    <section class="cart__section">
        <div class="container cart__container">
            <h1>Корзина</h1>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(empty($cart))
                <p>Ваша корзина пуста</p>
                <a href="{{ route('products.index') }}" class="btn-main">Перейти к каталогу</a>
            @else
                <form action="{{ route('cart.checkout') }}" method="POST" class="cart-form cart__content" id="cart-form">
                    @csrf

                    <div class="cart-items">
                        @foreach($cart as $variationId => $item)
                            <div class="cart-item" data-variation-id="{{ $variationId }}" data-price="{{ $item['price'] }}" data-stock="{{ $item['stock'] ?? 0 }}">
                                <div class="item-image">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @endif
                                </div>

                                <div class="item-info">
                                    <div class="item-name">{{ $item['name'] }}</div>
                                    @if($item['variation_name'])
                                        <div class="item-variation">{{ $item['variation_name'] }}</div>
                                    @endif
                                    <div class="item-price">{{ number_format($item['price'], 2) }} ₽</div>
                                </div>

                                <div class="item-quantity">
                                    <button type="button" class="quantity-btn quantity-minus" data-variation-id="{{ $variationId }}">-</button>
                                    <input type="number" name="quantities[{{ $variationId }}]"
                                           value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}" class="form-control quantity-input" data-variation-id="{{ $variationId }}">
                                    <button type="button" class="quantity-btn quantity-plus" data-variation-id="{{ $variationId }}">+</button>
                                </div>

                                <div class="item-total" id="item-total-{{ $variationId }}">
                                    {{ number_format($item['price'] * $item['quantity'], 2) }} ₽
                                </div>

                                <div class="item-remove">
                                    <a href="{{ route('cart.remove', $variationId) }}" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cart-summary">
                        <div class="cart-total">
                            <span>Итого:</span>
                            <span id="cart-total-price">{{ number_format($totalPrice, 2) }} ₽</span>
                        </div>

                        <div class="cart-delivery">
                            <h3>Способ получения</h3>
                            <div class="form-group">
                                <label class="form-choose">
                                    <input type="radio" name="delivery_type" value="pickup" checked>
                                    Самовывоз
                                </label>
                                <label class="form-choose">
                                    <input type="radio" name="delivery_type" value="delivery">
                                    Доставка
                                </label>
                            </div>

                            <div class="form-group delivery-address" style="display: none;">
                                <label for="delivery_address">Адрес доставки:</label>
                                <input type="text" id="delivery_address" name="delivery_address"
                                       value="{{ auth()->user()->address ?? '' }}" class="form-control">
                            </div>
                        </div>

                        <div class="cart-pickup-date">
                            <h3>Дата и время получения заказа</h3>
                            <p>Минимальное время получения заказа - через 1 час. Время работы: с 10:00 до 21:00</p>

                            <div class="form-group">
                                <label for="pickup_date">Выберите дату и время:</label>
                                <input type="datetime-local" id="pickup_date" name="pickup_date"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="cart-prepayment-notice" style="background-color: #fdf2f8; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #fbcfe8;">
                            <p style="margin: 0; font-size: 14px; color: #9d174d;">
                                После оформления заказа с вами свяжется наш менеджер по указанному номеру телефона для подтверждения заказа и внесения предоплаты. <strong>Предоплата составляет 50%</strong> от суммы заказа.
                            </p>
                        </div>

                        <div class="cart-actions">
                            <button type="submit" class="btn-main">Оформить заказ</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка переключения способа получения
            const deliveryRadios = document.querySelectorAll('input[name="delivery_type"]');
            const deliveryAddress = document.querySelector('.delivery-address');

            deliveryRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'delivery') {
                        deliveryAddress.style.display = 'block';
                    } else {
                        deliveryAddress.style.display = 'none';
                    }
                });
            });

            // Установка минимальной даты и времени для получения заказа
            const pickupDateInput = document.getElementById('pickup_date');
            const now = new Date();
            const minDate = new Date(now.getTime() + 60 * 60 * 1000); // Текущее время + 1 час

            // Форматируем дату в формат YYYY-MM-DDTHH:MM
            const year = minDate.getFullYear();
            const month = String(minDate.getMonth() + 1).padStart(2, '0');
            const day = String(minDate.getDate()).padStart(2, '0');
            const hours = String(minDate.getHours()).padStart(2, '0');
            const minutes = String(minDate.getMinutes()).padStart(2, '0');

            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            pickupDateInput.min = minDateTime;

            // Устанавливаем значение по умолчанию
            pickupDateInput.value = minDateTime;

            // Обработка изменения количества товара
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const quantityMinusButtons = document.querySelectorAll('.quantity-minus');
            const quantityPlusButtons = document.querySelectorAll('.quantity-plus');

            // Функция для обновления общей стоимости товара
            function updateItemTotal(variationId, quantity) {
                const cartItem = document.querySelector(`.cart-item[data-variation-id="${variationId}"]`);
                const price = parseFloat(cartItem.dataset.price);
                const itemTotalElement = document.getElementById(`item-total-${variationId}`);

                const newTotal = price * quantity;
                itemTotalElement.textContent = `${newTotal.toFixed(2)} ₽`;

                // Обновляем общую стоимость корзины
                updateCartTotal();
            }

            // Функция для обновления общей стоимости корзины
            function updateCartTotal() {
                let cartTotal = 0;

                document.querySelectorAll('.cart-item').forEach(item => {
                    const price = parseFloat(item.dataset.price);
                    const quantity = parseInt(item.querySelector('.quantity-input').value);
                    cartTotal += price * quantity;
                });

                const cartTotalElement = document.getElementById('cart-total-price');
                cartTotalElement.textContent = `${cartTotal.toFixed(2)} ₽`;
            }

            // Обработчики для кнопок уменьшения количества
            quantityMinusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const variationId = this.dataset.variationId;
                    const input = document.querySelector(`.quantity-input[data-variation-id="${variationId}"]`);
                    const cartItem = document.querySelector(`.cart-item[data-variation-id="${variationId}"]`);
                    const stock = parseInt(cartItem.dataset.stock);

                    let currentValue = parseInt(input.value);

                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                        updateItemTotal(variationId, currentValue - 1);
                    }
                });
            });

            // Обработчики для кнопок увеличения количества
            quantityPlusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const variationId = this.dataset.variationId;
                    const input = document.querySelector(`.quantity-input[data-variation-id="${variationId}"]`);
                    const cartItem = document.querySelector(`.cart-item[data-variation-id="${variationId}"]`);
                    const stock = parseInt(cartItem.dataset.stock);

                    let currentValue = parseInt(input.value);

                    if (currentValue < stock) {
                        input.value = currentValue + 1;
                        updateItemTotal(variationId, currentValue + 1);
                    } else {
                        // Показываем сообщение, если достигнуто максимальное количество
                        const message = document.createElement('div');
                        message.className = 'stock-warning';
                        message.textContent = 'Достигнуто максимальное количество товара';

                        // Удаляем предыдущее сообщение, если есть
                        const existingMessage = cartItem.querySelector('.stock-warning');
                        if (existingMessage) {
                            existingMessage.remove();
                        }

                        cartItem.appendChild(message);

                        // Удаляем сообщение через 3 секунды
                        setTimeout(() => {
                            message.remove();
                        }, 5000);
                    }
                });
            });

            // Обработчики для полей ввода количества
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const variationId = this.dataset.variationId;
                    const cartItem = document.querySelector(`.cart-item[data-variation-id="${variationId}"]`);
                    const stock = parseInt(cartItem.dataset.stock);

                    let newValue = parseInt(this.value);

                    // Проверяем, что значение в допустимом диапазоне
                    if (isNaN(newValue) || newValue < 1) {
                        newValue = 1;
                    } else if (newValue > stock) {
                        newValue = stock;

                        // Показываем сообщение, если достигнуто максимальное количество
                        const message = document.createElement('div');
                        message.className = 'stock-warning';
                        message.textContent = 'Достигнуто максимальное количество товара';

                        // Удаляем предыдущее сообщение, если есть
                        const existingMessage = cartItem.querySelector('.stock-warning');
                        if (existingMessage) {
                            existingMessage.remove();
                        }

                        cartItem.appendChild(message);

                        // Удаляем сообщение через 3 секунды
                        setTimeout(() => {
                            message.remove();
                        }, 5000);
                    }

                    this.value = newValue;
                    updateItemTotal(variationId, newValue);
                });
            });
        });
    </script>
@endsection
