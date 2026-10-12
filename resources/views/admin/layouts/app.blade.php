<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin').' - '.\App\Models\Setting::get('site_name', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <canvas id="bg-3d"></canvas>
    <div class="bg-overlay"></div>

    @php
        $nav = [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'active' => 'admin.dashboard'],
            ['route' => 'admin.products.index', 'label' => 'Produk', 'active' => 'admin.products.*'],
            ['route' => 'admin.categories.index', 'label' => 'Kategori', 'active' => 'admin.categories.*'],
            ['route' => 'admin.reviews.index', 'label' => 'Review', 'active' => 'admin.reviews.*'],
            ['route' => 'admin.statistics.index', 'label' => 'Statistik', 'active' => 'admin.statistics.*'],
            ['route' => 'admin.settings.edit', 'label' => 'Pengaturan', 'active' => 'admin.settings.*'],
        ];
    @endphp

    <div class="relative z-10 mx-auto flex min-h-screen max-w-7xl gap-6 px-4 py-5">
        {{-- Sidebar --}}
        <aside class="glass-strong sticky top-5 hidden h-[calc(100vh-2.5rem)] w-64 shrink-0 flex-col rounded-2xl p-4 lg:flex">
            <a href="{{ route('admin.dashboard') }}" class="mb-6 flex items-center gap-2 px-2">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 via-fuchsia-500 to-cyan-400 font-black text-white">G</span>
                <span class="font-extrabold tracking-tight text-white">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</span>
            </a>

            <nav class="flex flex-1 flex-col gap-1">
                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="rounded-xl px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs($item['active']) ? 'bg-brand-500/20 text-white ring-1 ring-brand-400/40' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-4 space-y-2 border-t border-white/10 pt-4">
                <a href="{{ route('home') }}" target="_blank" class="block rounded-xl px-3 py-2 text-sm text-slate-300 transition hover:bg-white/5 hover:text-white">Lihat Situs &rarr;</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl px-3 py-2 text-left text-sm text-rose-300 transition hover:bg-rose-500/10">Keluar</button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="min-w-0 flex-1">
            <header class="glass-strong mb-6 flex items-center justify-between gap-4 rounded-2xl px-4 py-3">
                <div>
                    <h1 class="text-lg font-bold text-white">@yield('heading', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('subheading', 'Kelola konten website affiliate kamu')</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-right text-xs sm:block">
                        <span class="block font-semibold text-white">{{ auth()->user()->name }}</span>
                        <span class="text-slate-400">{{ auth()->user()->email }}</span>
                    </span>
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-cyan-400 text-sm font-bold text-white">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
            </header>

            {{-- Mobile nav --}}
            <div class="mb-4 flex gap-2 overflow-x-auto lg:hidden">
                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="whitespace-nowrap rounded-xl px-3 py-2 text-xs font-medium transition
                       {{ request()->routeIs($item['active']) ? 'bg-brand-500/20 text-white ring-1 ring-brand-400/40' : 'glass text-slate-300' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            @if(session('status'))
                <div class="mb-4 rounded-xl border-l-4 border-emerald-400 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl border-l-4 border-rose-400 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                    <p class="font-semibold">Periksa kembali input kamu:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
