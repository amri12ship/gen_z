@extends('admin.layouts.app')

@section('title', 'Statistik')
@section('heading', 'Statistik')
@section('subheading', 'Pantau performa klik affiliate')

@section('content')
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="glass card-3d rounded-2xl p-5">
            <p class="text-xs text-slate-400">Total Klik</p>
            <p class="mt-1 text-3xl font-black text-white">{{ number_format($totalClicks, 0, ',', '.') }}</p>
        </div>
        <div class="glass card-3d rounded-2xl p-5">
            <p class="text-xs text-slate-400">Klik Hari Ini</p>
            <p class="mt-1 text-3xl font-black text-white">{{ number_format($clicksToday, 0, ',', '.') }}</p>
        </div>
        <div class="glass card-3d rounded-2xl p-5">
            <p class="text-xs text-slate-400">Klik Bulan Ini</p>
            <p class="mt-1 text-3xl font-black text-white">{{ number_format($clicksThisMonth, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="glass mt-6 rounded-2xl p-5">
        <h2 class="mb-4 font-bold text-white">Klik 14 Hari Terakhir</h2>
        <div class="flex h-48 items-end gap-2">
            @foreach($chart as $point)
                <div class="flex flex-1 flex-col items-center gap-2">
                    <div class="flex w-full items-end justify-center" style="height: 150px;">
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-brand-600 to-cyan-400 transition-all hover:from-brand-500 hover:to-cyan-300"
                             style="height: {{ max(4, (int) round($point['value'] / $maxChart * 100)) }}%"
                             title="{{ $point['value'] }} klik"></div>
                    </div>
                    <span class="whitespace-nowrap text-[9px] text-slate-500">{{ $point['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="glass mt-6 rounded-2xl p-5">
        <h2 class="mb-4 font-bold text-white">Top 10 Produk (berdasarkan klik)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">Produk</th>
                        <th class="px-3 py-3">Harga</th>
                        <th class="px-3 py-3 text-right">Klik</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $index => $product)
                        <tr class="border-b border-white/5">
                            <td class="px-3 py-3 font-bold text-brand-300">{{ $index + 1 }}</td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->imageUrl())
                                        <img src="{{ $product->imageUrl() }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @endif
                                    <span class="font-medium text-white">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-slate-300">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right font-bold text-emerald-300">{{ number_format((int) $product->clicks, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-12 text-center text-slate-400">Belum ada data klik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
