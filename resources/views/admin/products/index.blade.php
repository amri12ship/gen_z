@extends('admin.layouts.app')

@section('title', 'Produk')
@section('heading', 'Produk')
@section('subheading', 'Kelola daftar produk dan link affiliate')

@section('content')
    <div class="glass rounded-2xl p-4">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-1 items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
                       class="w-full max-w-xs rounded-xl bg-white/5 px-4 py-2 text-sm text-white placeholder-slate-500 outline-none ring-1 ring-white/10 focus:ring-brand-400">
                <button class="btn btn-ghost btn-sm">Cari</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah Produk</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3">Produk</th>
                        <th class="px-3 py-3">Kategori</th>
                        <th class="px-3 py-3">Harga</th>
                        <th class="px-3 py-3">Foto</th>
                        <th class="px-3 py-3">Klik</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b border-white/5 transition hover:bg-white/5">
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->imageUrl())
                                        <img src="{{ $product->imageUrl() }}" alt="" class="h-11 w-11 rounded-lg object-cover">
                                    @else
                                        <span class="grid h-11 w-11 place-items-center rounded-lg bg-gradient-to-br from-brand-600 to-cyan-500 font-bold text-white">{{ mb_substr($product->name, 0, 1) }}</span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-white">{{ $product->name }}</p>
                                        <div class="mt-0.5 flex gap-1">
                                            @if($product->is_trending)<span class="rounded bg-fuchsia-500/20 px-1.5 text-[10px] font-bold text-fuchsia-300">TRENDING</span>@endif
                                            @if($product->is_viral)<span class="rounded bg-cyan-500/20 px-1.5 text-[10px] font-bold text-cyan-300">VIRAL</span>@endif
                                            @if($product->is_recommended)<span class="rounded bg-amber-500/20 px-1.5 text-[10px] font-bold text-amber-300">REKOMENDASI</span>@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-slate-300">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-3 py-3 text-slate-300">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-slate-300">{{ $product->images_count }}</td>
                            <td class="px-3 py-3 text-slate-300">{{ number_format((int) $product->clicks, 0, ',', '.') }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->isActive() ? 'bg-emerald-500/15 text-emerald-300' : 'bg-slate-500/20 text-slate-300' }}">
                                    {{ $product->isActive() ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="rounded-lg bg-white/5 px-3 py-1.5 text-xs text-slate-200 hover:bg-white/10">Lihat</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg bg-brand-500/20 px-3 py-1.5 text-xs font-medium text-brand-200 hover:bg-brand-500/30">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-500/15 px-3 py-1.5 text-xs font-medium text-rose-300 hover:bg-rose-500/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center text-slate-400">Belum ada produk. <a href="{{ route('admin.products.create') }}" class="text-brand-300 hover:underline">Tambah sekarang</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
