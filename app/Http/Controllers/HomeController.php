<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $base = Product::query()->where('status', Product::STATUS_ACTIVE)->with('primaryImage');

        $trending = (clone $base)->where('is_trending', true)->latest()->take(8)->get();
        $viral = (clone $base)->where('is_viral', true)->latest()->take(8)->get();
        $recommended = (clone $base)->where('is_recommended', true)->latest()->take(8)->get();
        $latest = (clone $base)->latest()->take(8)->get();

        $categories = Category::query()
            ->withCount(['products' => fn ($q) => $q->where('status', Product::STATUS_ACTIVE)])
            ->orderBy('name')
            ->get();

        return view('home', [
            'trending' => $trending,
            'viral' => $viral,
            'recommended' => $recommended,
            'latest' => $latest,
            'categories' => $categories,
            'siteName' => Setting::get('site_name', config('app.name')),
            'tagline' => Setting::get('tagline', 'Temukan produk terbaik pilihan Gen-Z'),
            'totalProducts' => Product::query()->where('status', Product::STATUS_ACTIVE)->count(),
        ]);
    }
}
