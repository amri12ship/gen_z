@props(['product'])

@php
    $image = $product->imageUrl();
@endphp

<a href="{{ route('products.show', $product->slug) }}"
   data-tilt
   class="card-3d glass group flex flex-col overflow-hidden rounded-2xl">
    <div class="relative aspect-square overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $product->name }}"
                 loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-600 via-fuchsia-600 to-cyan-500">
                <span class="text-5xl font-black text-white/80">{{ mb_substr($product->name, 0, 1) }}</span>
            </div>
        @endif

        <div class="absolute left-3 top-3 flex flex-col gap-1">
            @if($product->is_trending)
                <span class="badge-pulse rounded-full bg-fuchsia-500/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Trending</span>
            @endif
            @if($product->is_viral)
                <span class="rounded-full bg-cyan-500/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Viral</span>
            @endif
        </div>

        @if($product->is_recommended)
            <span class="absolute right-3 top-3 rounded-full bg-amber-400/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-950">Recommended</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-3 p-4">
        <div>
            @if($product->category)
                <span class="text-[11px] font-semibold uppercase tracking-wide text-brand-300">{{ $product->category->name }}</span>
            @endif
            <h3 class="mt-1 line-clamp-2 font-semibold leading-snug text-white">{{ $product->name }}</h3>
        </div>

        <div class="mt-auto flex items-end justify-between gap-2">
            <div>
                <p class="text-gradient text-lg font-extrabold">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-xs text-slate-400">
                    <span class="text-amber-300">&#9733;</span> {{ number_format((float) $product->rating, 1) }}
                    &middot; {{ number_format((int) $product->sold_count, 0, ',', '.') }} terjual
                </p>
            </div>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-brand-200 transition group-hover:bg-brand-500 group-hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </div>
</a>
