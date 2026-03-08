<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variations', 'category'])
            ->where('is_active', true)
            ->whereHas('variations', function ($q) {
                $q->where('is_active', true);
            });

        $selectedCategory = null;

        // Фильтрация по категории
        if ($request->has('category') && $request->category) {
            $selectedCategory = Category::findOrFail($request->category);

            $query->where('category_id', $request->category);
        }

        // Поиск по названию или описанию
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Сортировка
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'rating_asc':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'asc');
                break;
            case 'rating_desc':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'sales_asc':
                $query->withMin('variations', 'sales_count')->orderBy('variations_min_sales_count', 'asc');
                break;
            case 'sales_desc':
                $query->withMax('variations', 'sales_count')->orderBy('variations_max_sales_count', 'desc');
                break;
            case 'price_asc':
                $query->withMin('variations', 'price')->orderBy('variations_min_price', 'asc');
                break;
            case 'price_desc':
                $query->withMax('variations', 'price')->orderBy('variations_max_price', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'selectedCategory'));
    }

    public function show($slug)
    {
        $product = Product::with(['variations', 'category', 'reviews' => function ($q) {
            $q->where('is_approved', true)->latest();
        }])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('products.show', compact('product'));
    }
}
