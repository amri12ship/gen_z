@extends('admin.layouts.app')

@section('title', 'Review')
@section('heading', 'Review')
@section('subheading', 'Setujui atau hapus review dari pengunjung')

@section('content')
    <div class="glass rounded-2xl p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3">Produk</th>
                        <th class="px-3 py-3">Pengunjung</th>
                        <th class="px-3 py-3">Rating</th>
                        <th class="px-3 py-3">Komentar</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-b border-white/5 transition hover:bg-white/5">
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    @if($review->product?->imageUrl())
                                        <img src="{{ $review->product->imageUrl() }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @endif
                                    <span class="font-medium text-white">{{ $review->product?->name ?? '(produk dihapus)' }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-slate-300">{{ $review->name }}</td>
                            <td class="px-3 py-3 text-amber-300">{{ str_repeat('★', $review->rating) }}</td>
                            <td class="px-3 py-3 max-w-sm text-slate-300">{{ $review->comment }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $review->is_approved ? 'bg-emerald-500/15 text-emerald-300' : 'bg-amber-500/15 text-amber-300' }}">
                                    {{ $review->is_approved ? 'Tampil' : 'Menunggu' }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="rounded-lg bg-brand-500/20 px-3 py-1.5 text-xs font-medium text-brand-200 hover:bg-brand-500/30">
                                            {{ $review->is_approved ? 'Sembunyikan' : 'Setujui' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-500/15 px-3 py-1.5 text-xs font-medium text-rose-300 hover:bg-rose-500/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-12 text-center text-slate-400">Belum ada review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $reviews->links() }}</div>
    </div>
@endsection
