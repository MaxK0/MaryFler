@extends('layout')

@section('content')
    <section class="products__section">
        <div class="container products__container">
            <h1>Мэри Флёр</h1>

            <div class="products__filters">
                <form action="{{ route('home') }}" method="GET" class="filters-form">
                    <div class="filter-group">
                        <label for="category">Категория:</label>
                        <select name="category" id="category">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Сортировка:</label>
                        <select name="sort" id="sort">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию
                            </option>
                            <option value="rating_asc" {{ request('sort') == 'rating_asc' ? 'selected' : '' }}>Рейтинг
                                (по возрастанию)
                            </option>
                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Рейтинг
                                (по убыванию)
                            </option>
                            <option value="sales_asc" {{ request('sort') == 'sales_asc' ? 'selected' : '' }}>
                                Популярность (по возрастанию)
                            </option>
                            <option value="sales_desc" {{ request('sort') == 'sales_desc' ? 'selected' : '' }}>
                                Популярность (по убыванию)
                            </option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена (по
                                возрастанию)
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена (по
                                убыванию)
                            </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="search">Поиск:</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Название или описание">
                    </div>

                    <button type="submit" class="btn-main">Применить</button>
                    <a href="{{ route('home') }}" class="btn-secondary">Сбросить</a>
                </form>
            </div>

            @if($products->count() > 0)
                <div class="products__grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <div class="product-card__image">
                                @if($product->variations->first() && $product->variations->first()->images)
                                    <div class="swiper product-swiper">
                                        <div class="swiper-wrapper">
                                            @foreach($product->variations->first()->images as $image)
                                                <div class="swiper-slide">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         alt="{{ $product->name }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="product-card__info">
                                <h3>{{ $product->name }}</h3>
                                <p>{{ Str::limit($product->description, 100) }}</p>
                                <div class="products-card__price__summary">
                                    <div class="product-card__price">
                                        от {{ number_format($product->variations->min('price'), 2) }} ₽
                                    </div>
                                    <div class="reviews__summary">
                                        <i class="fa-solid fa-star"></i>
                                        <div class="rating-value">{{ number_format($product->averageRating, 1) }}</div>
                                        <div class="reviews-count">{{ $product->reviews->count() }} отзывов</div>
                                    </div>
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn-main">Подробнее</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $products->links() }}
            @else
                <div class="empty-result">
                    <p>По вашему запросу ничего не найдено</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Инициализация свайпера для карточек товаров
            const productSwipers = document.querySelectorAll('.product-swiper');
            productSwipers.forEach(function (swiper) {
                new Swiper(swiper, {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    pagination: {
                        el: swiper.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    navigation: {
                        nextEl: swiper.querySelector('.swiper-button-next'),
                        prevEl: swiper.querySelector('.swiper-button-prev'),
                    },
                });
            });
        });
    </script>
@endpush
