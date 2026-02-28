@extends('layout')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <section class="profile__section">
        <div class="container profile__container">
            <h1>Заказ #{{ $order->id }}</h1>

            <div class="order-details">
                <div class="order-info">
                    <div class="info-item">
                        <span>Дата заказа:</span>
                        <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="info-item">
                        <span>Статус:</span>
                        <span class="order-status {{ $order->status }}">{{ $order->status }}</span>
                    </div>

                    @if($order->estimated_completion)
                        <div class="info-item">
                            <span>Ожидаемое время готовности:</span>
                            <span>{{ $order->estimated_completion->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif

                    <div class="info-item">
                        <span>Способ получения:</span>
                        @if($order->delivery_type === 'pickup')
                            <span>Самовывоз</span>
                        @else
                            <span>Доставка</span>
                        @endif
                    </div>

                    @if($order->delivery_type === 'delivery' && $order->delivery_address)
                        <div class="info-item">
                            <span>Адрес доставки:</span>
                            <span>{{ $order->delivery_address }}</span>
                        </div>
                    @endif
                </div>

                <div class="order-items">
                    <h2>Товары в заказе</h2>

                    @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @if($item->productVariation->images && count($item->productVariation->images) > 0)
                                    <img src="{{ asset('storage/' . $item->productVariation->images[0]) }}" alt="{{ $item->productVariation->product->name }}">
                                @endif
                            </div>

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

                <div class="order-total">
                    <span>Итого:</span>
                    <span>{{ number_format($order->total_price, 2) }} ₽</span>
                </div>
            </div>

            <div class="order-actions">
                <a href="{{ route('profile.orders') }}" class="btn-secondary">Вернуться к списку заказов</a>
            </div>
        </div>
    </section>
@endsection
