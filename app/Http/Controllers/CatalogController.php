<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function storeReview(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $product->reviews()->create($data + ['is_approved' => false]);

        return redirect()
            ->route('products.show', $product->slug)
            ->with('status', 'Terima kasih! Review kamu sedang menunggu persetujuan admin.');
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->where('status', Product::STATUS_ACTIVE)
            ->with(['primaryImage', 'category'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->toString();

                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
            })
            ->when($request->input('sort') === 'popular', fn ($q) => $q->orderByDesc('clicks'))
            ->when($request->input('sort') === 'rating', fn ($q) => $q->orderByDesc('rating'))
            ->when($request->input('sort') === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->input('sort') === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->with(['images', 'category', 'approvedReviews'])
            ->firstOrFail();

        $related = Product::query()
            ->where('status', Product::STATUS_ACTIVE)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->with('primaryImage')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
