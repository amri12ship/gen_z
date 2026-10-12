@extends('layouts.public')

@section('title', 'Semua Produk - '.\App\Models\Setting::get('site_name', config('app.name')))

@section('content')
    <section class="mx-auto max-w-6xl px-4 pb-8 pt-12">
        <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Semua Produk</h1>
        <p class="mt-2 text-sm text-slate-400">Jelajahi kurasi produk berdasarkan kategori, harga, dan popularitas.</p>

        <form method="GET" action="{{ route('products.index') }}" class="glass mt-6 flex flex-col gap-3 rounded-2xl p-4 sm:flex-row sm:items-center">
            <div class="flex flex-1 items-center gap-2 rounded-xl bg-white/5 px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
                       class="w-full bg-transparent text-sm text-white placeholder-slate-400 outline-none">
            </div>

            <select name="category" class="rounded-xl bg-white/5 px-3 py-2 text-sm text-white outline-none [&>option]:bg-slate-900">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="rounded-xl bg-white/5 px-3 py-2 text-sm text-white outline-none [&>option]:bg-slate-900">
                <option value="">Terbaru</option>
                <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
                <option value="rating" @selected(request('sort') === 'rating')>Rating tertinggi</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga termurah</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga termahal</option>
            </select>

            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>
    </section>

    <section class="mx-auto max-w-6xl px-4 pb-12">
        @if($products->isEmpty())
            <div class="glass rounded-2xl px-6 py-16 text-center">
                <p class="text-lg font-semibold text-white">Produk tidak ditemukan</p>
                <p class="mt-2 text-sm text-slate-400">Coba kata kunci atau filter lain.</p>
                <a href="{{ route('products.index') }}" class="btn btn-ghost mt-6">Reset filter</a>
            </div>
        @else
            <p class="mb-4 text-sm text-slate-400">Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk</p>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection
