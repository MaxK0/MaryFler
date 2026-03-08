<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $totalPrice = 0;

        foreach ($cart as $variationId => $item) {
            $totalPrice += $item['price'] * $item['quantity'];

            // Получаем информацию о наличии товара
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $cart[$variationId]['stock'] = $variation->stock;
            }
        }

        // Обновляем корзину в сессии с информацией о наличии
        Session::put('cart', $cart);

        return view('cart.index', compact('cart', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $variationId = $request->input('variation_id');
        $quantity = $request->input('quantity', 1);

        $variation = ProductVariation::with('product')->findOrFail($variationId);

        if ($variation->stock < $quantity) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$variationId])) {
            $cart[$variationId]['quantity'] += $quantity;
        } else {
            $cart[$variationId] = [
                'variation_id' => $variation->id,
                'name' => $variation->product->name,
                'variation_name' => $variation->name,
                'price' => $variation->price,
                'quantity' => $quantity,
                'image' => $variation->images[0] ?? null,
                'stock' => $variation->stock, // Добавляем информацию о наличии
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request)
    {
        $cart = Session::get('cart', []);

        foreach ($request->input('quantities', []) as $variationId => $quantity) {
            if (isset($cart[$variationId])) {
                $variation = ProductVariation::findOrFail($variationId);

                if ($variation->stock < $quantity) {
                    return back()->with('error', 'Недостаточно товара на складе');
                }

                if ($quantity <= 0) {
                    unset($cart[$variationId]);
                } else {
                    $cart[$variationId]['quantity'] = $quantity;
                }
            }
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Корзина обновлена');
    }

    public function remove($variationId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$variationId])) {
            unset($cart[$variationId]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Товар удален из корзины');
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);

        foreach ($request->input('quantities', []) as $variationId => $quantity) {
            if (isset($cart[$variationId])) {
                $variation = ProductVariation::findOrFail($variationId);

                if ($variation->stock < $quantity) {
                    return back()->with('error', 'Недостаточно товара на складе');
                }

                if ($quantity <= 0) {
                    unset($cart[$variationId]);
                } else {
                    $cart[$variationId]['quantity'] = $quantity;
                }
            }
        }

        Session::put('cart', $cart);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Корзина пуста');
        }

        // Валидация выбранного времени получения
        $request->validate([
            'pickup_date' => 'required|date|after:now',
        ]);

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $user = auth()->user();

        $pickupDate = \Carbon\Carbon::parse($request->input('pickup_date'));

        // Проверяем, что выбранное время в рабочие часы (10:00 - 21:00)
        $pickupTime = $pickupDate->format('H:i');
        if ($pickupTime < '10:00' || $pickupTime > '21:00') {
            return back()->with('error', 'Время получения заказа должно быть с 10:00 до 21:00');
        }

        // Проверяем, что выбранное время не раньше текущего времени + 1 час
        $minPickupDate = now()->addHour();
        if ($pickupDate->lt($minPickupDate)) {
            return back()->with('error', 'Минимальное время получения заказа - через 1 час');
        }

        $order = $user->orders()->create([
            'status' => 'Новое',
            'total_price' => $totalPrice,
            'delivery_type' => $request->input('delivery_type', 'pickup'),
            'delivery_address' => $request->input('delivery_type') === 'delivery'
                ? $request->input('delivery_address', $user->address)
                : null,
            'pickup_date' => $pickupDate,
        ]);

        foreach ($cart as $variationId => $item) {
            $order->items()->create([
                'product_variation_id' => $variationId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Уменьшаем количество товара на складе
            $variation = ProductVariation::findOrFail($variationId);
            $variation->stock -= $item['quantity'];
            $variation->sales_count += $item['quantity'];
            $variation->save();
        }

        // Очищаем корзину
        Session::forget('cart');

        return redirect()->route('profile.orders')->with('success', 'Заказ успешно оформлен');
    }
}
