<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Support\Uploads;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create', ['category' => new Category()]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = Uploads::store($request->file('image'), 'categories');
        }

        unset($data['image']);

        Category::query()->create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            Uploads::delete($category->image_path);
            $data['image_path'] = Uploads::store($request->file('image'), 'categories');
        }

        unset($data['image']);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        Uploads::delete($category->image_path);
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil dihapus.');
    }
}
