@extends('layouts.public')

@php
    $images = $product->images;
    $primary = $images->first();
    $primaryUrl = $primary ? $primary->url() : null;
    $reviewCount = $product->approvedReviews->count();
    $avgRating = $reviewCount ? round($product->approvedReviews->avg('rating'), 1) : (float) $product->rating;
@endphp

@section('title', $product->name.' - '.\App\Models\Setting::get('site_name', config('app.name')))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 150))
@if($primaryUrl)
    @section('og_image', $primaryUrl)
@endif

@section('content')
    <section class="mx-auto max-w-6xl px-4 pt-10">
        <nav class="mb-6 flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-white">Produk</a>
            @if($product->category)
                <span>/</span>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-white">{{ $product->category->name }}</a>
            @endif
        </nav>

        <div class="grid gap-8 lg:grid-cols-2">
            {{-- Gallery --}}
            <div class="reveal">
                <div class="glass overflow-hidden rounded-3xl">
                    <div class="relative aspect-square">
                        @if($primaryUrl)
                            <img id="main-image" src="{{ $primaryUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-600 via-fuchsia-600 to-cyan-500">
                                <span class="text-7xl font-black text-white/80">{{ mb_substr($product->name, 0, 1) }}</span>
                            </div>
                        @endif

                        <div class="absolute left-4 top-4 flex flex-col gap-1">
                            @if($product->is_trending)
                                <span class="rounded-full bg-fuchsia-500/90 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white">Trending</span>
                            @endif
                            @if($product->is_viral)
                                <span class="rounded-full bg-cyan-500/90 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white">Viral</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($images->count() > 1)
                    <div class="mt-3 grid grid-cols-4 gap-3">
                        @foreach($images as $image)
                            <button type="button" data-thumb data-src="{{ $image->url() }}"
                                    class="glass overflow-hidden rounded-xl ring-2 ring-transparent transition hover:ring-brand-400 data-[active=true]:ring-brand-500"
                                    data-active="{{ $loop->first ? 'true' : 'false' }}">
                                <img src="{{ $image->url() }}" alt="{{ $product->name }} {{ $loop->iteration }}" class="aspect-square w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="reveal">
                @if($product->category)
                    <span class="inline-block rounded-full bg-brand-500/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-200">
                        {{ $product->category->name }}
                    </span>
                @endif

                <h1 class="mt-3 text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl">{{ $product->name }}</h1>

                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-300">
                    <span class="flex items-center gap-1">
                        <span class="text-amber-300">&#9733;</span> {{ number_format($avgRating, 1) }} ({{ $reviewCount }} review)
                    </span>
                    <span class="h-1 w-1 rounded-full bg-slate-600"></span>
                    <span>{{ number_format((int) $product->sold_count, 0, ',', '.') }} terjual</span>
                    <span class="h-1 w-1 rounded-full bg-slate-600"></span>
                    <span>{{ number_format((int) $product->clicks, 0, ',', '.') }} klik affiliate</span>
                </div>

                <div class="mt-6">
                    <p class="text-gradient text-4xl font-black">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('affiliate.go', $product) }}" target="_blank" rel="noopener sponsored" class="btn btn-primary flex-1 text-base sm:flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Beli Sekarang
                    </a>
                    <button type="button" onclick="navigator.clipboard&&navigator.clipboard.writeText('{{ $product->affiliate_url }}')"
                            class="btn btn-ghost">Salin Link</button>
                </div>

                <div class="glass mt-8 rounded-2xl p-5">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-300">Deskripsi</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-300">{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews --}}
    <section class="mx-auto max-w-6xl px-4 py-14">
        <h2 class="text-2xl font-extrabold tracking-tight text-white">Review Pembeli ({{ $reviewCount }})</h2>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                @forelse($product->approvedReviews as $review)
                    <div class="glass rounded-2xl p-5">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-white">{{ $review->name }}</p>
                            <p class="text-amber-300">{{ str_repeat('★', $review->rating) }}<span class="text-slate-600">{{ str_repeat('★', 5 - $review->rating) }}</span></p>
                        </div>
                        <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $review->comment }}</p>
                        <p class="mt-2 text-xs text-slate-500">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                @empty
                    <div class="glass rounded-2xl p-8 text-center text-sm text-slate-400">
                        Belum ada review. Jadilah yang pertama!
                    </div>
                @endforelse
            </div>

            <div class="glass h-fit rounded-2xl p-5">
                <h3 class="font-bold text-white">Tulis Review</h3>
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-300">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl bg-white/5 px-3 py-2 text-sm text-white outline-none ring-1 ring-white/10 focus:ring-brand-400">
                        @error('name')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-300">Rating</label>
                        <select name="rating" class="w-full rounded-xl bg-white/5 px-3 py-2 text-sm text-white outline-none ring-1 ring-white/10 [&>option]:bg-slate-900 focus:ring-brand-400">
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-300">Komentar</label>
                        <textarea name="comment" rows="3" required
                                  class="w-full rounded-xl bg-white/5 px-3 py-2 text-sm text-white outline-none ring-1 ring-white/10 focus:ring-brand-400">{{ old('comment') }}</textarea>
                        @error('comment')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Kirim Review</button>
                    <p class="text-center text-xs text-slate-500">Review tampil setelah disetujui admin.</p>
                </form>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 pb-16">
            <h2 class="mb-5 text-2xl font-extrabold tracking-tight text-white">Produk Serupa</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif

    <script>
        document.querySelectorAll('[data-thumb]').forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const main = document.getElementById('main-image');
                if (!main) return;
                main.src = thumb.dataset.src;
                document.querySelectorAll('[data-thumb]').forEach((t) => t.dataset.active = 'false');
                thumb.dataset.active = 'true';
            });
        });
    </script>
@endsection
