<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'clicks' => AffiliateClick::query()->count(),
            'pending_reviews' => Review::query()->where('is_approved', false)->count(),
        ];

        $topProducts = Product::query()
            ->with('primaryImage')
            ->orderByDesc('clicks')
            ->take(5)
            ->get();

        $recent = Product::query()
            ->with('primaryImage')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'topProducts', 'recent'));
    }
}
