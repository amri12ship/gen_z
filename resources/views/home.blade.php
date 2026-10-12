@extends('layouts.public')

@section('title', \App\Models\Setting::get('site_name', config('app.name')).' - '.$tagline)

@section('content')
    <section class="mx-auto max-w-6xl px-4 pb-6 pt-14 sm:pt-20">
        <div class="mx-auto max-w-3xl text-center">
            <span class="badge-pulse inline-flex items-center gap-2 rounded-full border border-brand-400/40 bg-brand-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-brand-200">
                <span class="h-1.5 w-1.5 rounded-full bg-fuchsia-400"></span>
                Kurasi produk pilihan
            </span>

            <h1 class="mt-6 text-4xl font-black leading-tight tracking-tight text-white sm:text-6xl">
                Temukan <span class="text-gradient">produk paling worth it</span> buat kamu
            </h1>

            <p class="mx-auto mt-5 max-w-xl text-base text-slate-300 sm:text-lg">
                {{ $tagline }}
            </p>

            <form action="{{ route('products.index') }}" method="GET" class="mx-auto mt-8 flex max-w-xl items-center gap-2">
                <div class="glass flex flex-1 items-center gap-2 rounded-2xl px-4 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" name="q" placeholder="Cari produk, misal: TWS, serum, hoodie..."
                           class="w-full bg-transparent text-sm text-white placeholder-slate-400 outline-none">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>

            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="glass rounded-2xl px-3 py-4">
                    <p class="text-2xl font-extrabold text-white sm:text-3xl">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-400">Produk aktif</p>
                </div>
                <div class="glass rounded-2xl px-3 py-4">
                    <p class="text-2xl font-extrabold text-white sm:text-3xl">{{ number_format($categories->count(), 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-400">Kategori</p>
                </div>
                <div class="glass rounded-2xl px-3 py-4">
                    <p class="text-2xl font-extrabold text-white sm:text-3xl">100%</p>
                    <p class="mt-1 text-xs text-slate-400">Link terverifikasi</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-2">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="glass rounded-full px-4 py-2 text-sm font-medium text-slate-200 transition hover:-translate-y-0.5 hover:text-white">
                        {{ $category->name }}
                        <span class="ml-1 text-xs text-brand-300">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <div class="mx-auto flex max-w-6xl flex-col gap-14 px-4 py-12">
        <x-product-section id="trending" title="Lagi Trending" subtitle="Paling banyak dicari minggu ini"
            :products="$trending" :href="route('products.index')" />
        <x-product-section id="viral" title="Sedang Viral" subtitle="Ramai dibicarakan di media sosial"
            :products="$viral" :href="route('products.index')" />
        <x-product-section id="recommended" title="Rekomendasi Kami" subtitle="Pilihan editor paling layak beli"
            :products="$recommended" :href="route('products.index')" />
        <x-product-section id="latest" title="Baru Ditambahkan" subtitle="Update produk terbaru"
            :products="$latest" :href="route('products.index')" />

        @if($trending->isEmpty() && $viral->isEmpty() && $recommended->isEmpty() && $latest->isEmpty())
            <div class="glass rounded-2xl px-6 py-16 text-center">
                <p class="text-lg font-semibold text-white">Belum ada produk</p>
                <p class="mt-2 text-sm text-slate-400">Tambahkan produk lewat admin panel untuk menampilkannya di sini.</p>
                <a href="{{ route('admin.login') }}" class="btn btn-primary mt-6">Masuk Admin</a>
            </div>
        @endif
    </div>
@endsection
