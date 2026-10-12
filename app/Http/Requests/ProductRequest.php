<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->filled('slug')
                ? Str::slug($this->input('slug'))
                : Str::slug((string) $this->input('name')),
            'is_trending' => $this->boolean('is_trending'),
            'is_viral' => $this->boolean('is_viral'),
            'is_recommended' => $this->boolean('is_recommended'),
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sold_count' => ['nullable', 'integer', 'min:0'],
            'affiliate_url' => ['required', 'url', 'max:2048'],
            'is_trending' => ['boolean'],
            'is_viral' => ['boolean'],
            'is_recommended' => ['boolean'],
            'status' => ['required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
            'images' => ['nullable', 'array', 'max:'.Product::MAX_IMAGES],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama produk',
            'category_id' => 'kategori',
            'affiliate_url' => 'link affiliate',
            'price' => 'harga',
            'images.*' => 'foto produk',
        ];
    }
}
