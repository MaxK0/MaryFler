@extends('layout')

@section('title', $product->name)

@section('content')
    <section class="product__section">
        <div class="container product__container">
            <div class="product__details">
                <div class="product__gallery">
                    <div class="swiper product-gallery-swiper">
                        <div class="swiper-wrapper">
                            @foreach($product->variations->first()->images ?? [] as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <div class="product__info">
                    <h1>{{ $product->name }}</h1>
                    <p class="product__category">{{ $product->category->name }}</p>

                    @if($product->variations->count() > 1)
                        <div class="product__variations">
                            <h3>Выберите вариант:</h3>
                            <div class="variations-list">
                                @foreach($product->variations as $index => $variation)
                                    <div class="variation-item {{ $index === 0 ? 'active' : '' }}"
                                         data-variation-id="{{ $variation->id }}"
                                         data-price="{{ $variation->price }}"
                                         data-stock="{{ $variation->stock }}"
                                         data-description="{!! $variation->description !!}">
                                        {{ $variation->name ?: 'Вариант ' . ($index + 1) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="product__price">
                        <span class="price-value">{{ number_format($product->variations->first()->price, 2) }}</span> ₽
                    </div>

                    <div class="product__stock">
                        @if($product->variations->first()->stock > 0)
                            <span class="in-stock">В наличии: {{ $product->variations->first()->stock }} шт.</span>
                        @else
                            <span class="out-of-stock">Нет в наличии</span>
                        @endif
                    </div>

                    <div class="product__actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus">-</button>
                            <input type="text" name="quantity" value="1" min="1" max="{{ $product->variations->first()->stock }}" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="variation_id" value="{{ $product->variations->first()->id }}">
                            <input type="hidden" name="quantity" value="1" class="cart-quantity">
                            <button type="submit" class="btn-main" {{ $product->variations->first()->stock <= 0 ? 'disabled' : '' }}>
                                {{ $product->variations->first()->stock > 0 ? 'Добавить в корзину' : 'Нет в наличии' }}
                            </button>
                        </form>
                    </div>

                    <div class="product__description">
                        <h3>Описание</h3>
                        <div>{!! nl2br(e($product->description)) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews__section">
        <div class="container reviews__container">
            <h2>Отзывы</h2>

            <div class="reviews__summary">
                <div class="average-rating">
                    <div class="rating-value">{{ number_format($product->averageRating, 1) }}</div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->averageRating))
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="reviews-count">{{ $product->reviews->count() }} отзывов</div>
                </div>
            </div>

            @auth
                @if(auth()->user()->orders()->where('id', $product->id)->where('status', 'Получено')->exists())
                    <div class="review-form-container">
                        <h3>Оставить отзыв</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="review-form">
                            @csrf
                            <div class="form-group">
                                <label for="rating">Оценка:</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }}>
                                        <label for="rating-{{ $i }}">
                                            @if($i === 1)
                                                <i class="fa-regular fa-star"></i>
                                            @else
                                                <i class="fa-solid fa-star"></i>
                                            @endif
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comment">Ваш отзыв:</label>
                                <textarea id="comment" name="comment" rows="5" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="images">Фотографии (максимум 5):</label>
                                <input type="file" id="images" name="images[]" multiple accept="image/*">
                            </div>

                            <button type="submit" class="btn-main">Отправить отзыв</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="login-to-review">
                    <p>Для оставления отзыва необходимо <a href="{{ route('login') }}">войти</a> в аккаунт</p>
                </div>
            @endauth

            <div class="reviews__list">
                @foreach($product->reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-author">
                                <div class="author-avatar">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="author-name">{{ $review->user->name }}</div>
                            </div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="review-content">
                            <p>{{ $review->comment }}</p>
                        </div>

                        @if($review->images && count($review->images) > 0)
                            <div class="review-images">
                                @foreach($review->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Изображение отзыва">
                                @endforeach
                            </div>
                        @endif

                        <div class="review-date">
                            {{ $review->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                @endforeach

                @if($product->reviews->count() === 0)
                    <div class="no-reviews">
                        <p>Отзывов пока нет</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация свайпера для галереи товара
            const gallerySwiper = new Swiper('.product-gallery-swiper', {
                slidesPerView: 1,
                spaceBetween: 10,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Обработка выбора вариации товара
            const variationItems = document.querySelectorAll('.variation-item');
            const priceValue = document.querySelector('.price-value');
            const stockElement = document.querySelector('.product__stock span');
            const quantityInput = document.querySelector('.quantity-input');
            const variationDescription = document.querySelector('.product__description p');
            const addToCartButton = document.querySelector('.add-to-cart-form button');
            const variationIdInput = document.querySelector('.add-to-cart-form input[name="variation_id"]');

            variationItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Убираем активный класс у всех элементов
                    variationItems.forEach(el => el.classList.remove('active'));
                    // Добавляем активный класс текущему элементу
                    this.classList.add('active');

                    // Обновляем цену
                    const price = this.getAttribute('data-price');
                    priceValue.textContent = price.replace('.', ',');

                    // Обновляем информацию о наличии
                    const stock = this.getAttribute('data-stock');
                    if (stock > 0) {
                        stockElement.textContent = 'В наличии: ' + stock + ' шт.';
                        stockElement.className = 'in-stock';
                        quantityInput.max = stock;
                        addToCartButton.disabled = false;
                        addToCartButton.textContent = 'Добавить в корзину';
                    } else {
                        stockElement.textContent = 'Нет в наличии';
                        stockElement.className = 'out-of-stock';
                        quantityInput.max = 0;
                        addToCartButton.disabled = true;
                        addToCartButton.textContent = 'Нет в наличии';
                    }

                    // Обновляем ID вариации
                    const variationId = this.getAttribute('data-variation-id');
                    variationIdInput.value = variationId;

                    // Сбрасываем количество
                    quantityInput.value = 1;
                    document.querySelector('.cart-quantity').value = 1;

                    // Обновляем описание
                    const description = this.getAttribute('data-description');
                    variationDescription.textContent = description;
                });
            });

            // Обработка изменения количества
            const minusBtn = document.querySelector('.quantity-btn.minus');
            const plusBtn = document.querySelector('.quantity-btn.plus');
            const cartQuantity = document.querySelector('.cart-quantity');

            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    cartQuantity.value = currentValue - 1;
                }
            });

            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                let maxValue = parseInt(quantityInput.max);
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                    cartQuantity.value = currentValue + 1;
                }
            });

            quantityInput.addEventListener('change', function() {
                let currentValue = parseInt(this.value);
                let maxValue = parseInt(this.max);

                if (currentValue < 1) {
                    this.value = 1;
                } else if (currentValue > maxValue) {
                    this.value = maxValue;
                }

                cartQuantity.value = this.value;
            });

            // Обработка оценки в форме отзыва
            const ratingInputs = document.querySelectorAll('.rating-input input');
            const ratingLabels = document.querySelectorAll('.rating-input label');

            ratingInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    ratingLabels.forEach((label, labelIndex) => {
                        if (labelIndex <= index) {
                            label.innerHTML = '<i class="fa-solid fa-star"></i>';
                        } else {
                            label.innerHTML = '<i class="fa-regular fa-star"></i>';
                        }
                    });
                });
            });
        });
    </script>
@endpush
