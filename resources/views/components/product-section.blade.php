@props(['title', 'subtitle' => null, 'products', 'id' => null, 'href' => null])

@if($products->isNotEmpty())
    <section id="{{ $id }}" class="reveal">
        <div class="mb-5 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $title }}</h2>
                @if($subtitle)
                    <p class="mt-1 text-sm text-slate-400">{{ $subtitle }}</p>
                @endif
            </div>
            @if($href)
                <a href="{{ $href }}" class="hidden text-sm font-semibold text-brand-300 hover:text-brand-200 sm:inline-flex">Lihat semua &rarr;</a>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endif
