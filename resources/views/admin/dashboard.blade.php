@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Ringkasan performa website affiliate kamu')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Total Produk', 'value' => $stats['products'], 'accent' => 'from-brand-500 to-fuchsia-500'],
                ['label' => 'Kategori', 'value' => $stats['categories'], 'accent' => 'from-cyan-500 to-blue-500'],
                ['label' => 'Klik Affiliate', 'value' => $stats['clicks'], 'accent' => 'from-emerald-500 to-teal-500'],
                ['label' => 'Review Menunggu', 'value' => $stats['pending_reviews'], 'accent' => 'from-amber-500 to-orange-500'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="glass card-3d rounded-2xl p-5">
                <div class="mb-3 h-1.5 w-12 rounded-full bg-gradient-to-r {{ $card['accent'] }}"></div>
                <p class="text-3xl font-black text-white">{{ number_format($card['value'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="glass rounded-2xl p-5">
            <h2 class="mb-4 font-bold text-white">Produk Paling Banyak Diklik</h2>
            @forelse($topProducts as $product)
                <div class="flex items-center gap-3 border-b border-white/5 py-3 last:border-0">
                    @if($product->imageUrl())
                        <img src="{{ $product->imageUrl() }}" alt="" class="h-11 w-11 rounded-lg object-cover">
                    @else
                        <span class="grid h-11 w-11 place-items-center rounded-lg bg-gradient-to-br from-brand-600 to-cyan-500 font-bold text-white">{{ mb_substr($product->name, 0, 1) }}</span>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-bold text-emerald-300">{{ number_format((int) $product->clicks, 0, ',', '.') }} klik</span>
                </div>
            @empty
                <p class="py-8 text-center text-sm text-slate-400">Belum ada data klik.</p>
            @endforelse
        </div>

        <div class="glass rounded-2xl p-5">
            <h2 class="mb-4 font-bold text-white">Produk Terbaru</h2>
            @forelse($recent as $product)
                <div class="flex items-center gap-3 border-b border-white/5 py-3 last:border-0">
                    @if($product->imageUrl())
                        <img src="{{ $product->imageUrl() }}" alt="" class="h-11 w-11 rounded-lg object-cover">
                    @else
                        <span class="grid h-11 w-11 place-items-center rounded-lg bg-gradient-to-br from-fuchsia-600 to-cyan-500 font-bold text-white">{{ mb_substr($product->name, 0, 1) }}</span>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400">{{ $product->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $product->isActive() ? 'bg-brand-500/15 text-brand-200' : 'bg-slate-500/20 text-slate-300' }}">
                        {{ $product->isActive() ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            @empty
                <p class="py-8 text-center text-sm text-slate-400">Belum ada produk.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Kelola Produk</a>
    </div>
@endsection
