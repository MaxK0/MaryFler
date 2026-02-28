<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:2048'],
        ]);

        $product = Product::findOrFail($productId);

        // Проверяем, покупал ли пользователь этот товар
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', 'Получено')
            ->whereHas('items', function ($query) use ($product) {
                $query->whereHas('productVariation', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Вы можете оставить отзыв только на полученный товар');
        }

        // Проверяем, не оставлял ли пользователь уже отзыв
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв на этот товар');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $images,
        ]);

        return back()->with('success', 'Отзыв отправлен на модерацию');
    }
}
