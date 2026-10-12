<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\Uploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with(['primaryImage', 'category'])
            ->withCount('images')
            ->when(request('q'), function ($query, $term) {
                $query->where('name', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'product' => new Product(['status' => Product::STATUS_ACTIVE, 'rating' => 5]),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        unset($data['images'], $data['delete_images']);

        $product = Product::query()->create($this->normalize($data));

        $this->attachImages($product, $request->file('images', []));
        $this->resequenceImages($product);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        unset($data['images'], $data['delete_images']);

        $product->update($this->normalize($data));

        $deleteIds = array_filter((array) $request->input('delete_images', []));

        if ($deleteIds) {
            $images = $product->images()->whereIn('id', $deleteIds)->get();

            foreach ($images as $image) {
                Uploads::delete($image->image_path);
                $image->delete();
            }
        }

        $this->attachImages($product, $request->file('images', []));
        $this->resequenceImages($product);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Uploads::delete($image->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalize(array $data): array
    {
        $data['price'] = $data['price'] ?? 0;
        $data['rating'] = $data['rating'] ?? 0;
        $data['sold_count'] = $data['sold_count'] ?? 0;

        return $data;
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    protected function attachImages(Product $product, array $files): void
    {
        $order = (int) $product->images()->max('sort_order');

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = Uploads::store($file, 'products');

            $product->images()->create([
                'image_path' => $path,
                'sort_order' => ++$order,
            ]);
        }
    }

    protected function resequenceImages(Product $product): void
    {
        $order = 1;

        foreach ($product->images()->orderBy('sort_order')->orderBy('id')->get() as $image) {
            if ((int) $image->sort_order !== $order) {
                $image->update(['sort_order' => $order]);
            }

            $order++;
        }
    }
}
