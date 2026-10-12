@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('heading', 'Kategori')
@section('subheading', 'Kelola kategori produk')

@section('content')
    <div class="glass rounded-2xl p-4">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3">Kategori</th>
                        <th class="px-3 py-3">Slug</th>
                        <th class="px-3 py-3">Jumlah Produk</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-b border-white/5 transition hover:bg-white/5">
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    @if($category->imageUrl())
                                        <img src="{{ $category->imageUrl() }}" alt="" class="h-11 w-11 rounded-lg object-cover">
                                    @else
                                        <span class="grid h-11 w-11 place-items-center rounded-lg bg-gradient-to-br from-brand-600 to-cyan-500 font-bold text-white">{{ mb_substr($category->name, 0, 1) }}</span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-white">{{ $category->name }}</p>
                                        <p class="max-w-xs truncate text-xs text-slate-400">{{ $category->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-slate-300">{{ $category->slug }}</td>
                            <td class="px-3 py-3 text-slate-300">{{ $category->products_count }}</td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg bg-brand-500/20 px-3 py-1.5 text-xs font-medium text-brand-200 hover:bg-brand-500/30">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini? Produk terkait tidak akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-500/15 px-3 py-1.5 text-xs font-medium text-rose-300 hover:bg-rose-500/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-12 text-center text-slate-400">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
@endsection
