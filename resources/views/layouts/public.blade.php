<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Models\Setting::get('site_name', config('app.name')))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('description', 'Kurasi produk terbaik pilihan Gen-Z.'))">

    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <canvas id="bg-3d"></canvas>
    <div class="bg-overlay"></div>

    <header class="sticky top-0 z-40">
        <nav class="glass-strong mx-auto mt-4 flex max-w-6xl items-center justify-between gap-4 rounded-2xl px-4 py-3 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 via-fuchsia-500 to-cyan-400 font-black text-white shadow-lg">G</span>
                <span class="text-lg font-extrabold tracking-tight text-white">
                    {{ \App\Models\Setting::get('site_name', config('app.name')) }}
                </span>
            </a>

            <div class="hidden items-center gap-1 sm:flex">
                <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">Home</a>
                <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">Produk</a>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">Terpopuler</a>
            </div>

            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">Dashboard</a>
                @endif
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm">Admin</a>
            @endauth
        </nav>
    </header>

    @if(session('status'))
        <div class="mx-auto mt-4 max-w-6xl px-4">
            <div class="glass rounded-xl border-l-4 border-brand-400 px-4 py-3 text-sm text-brand-100">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="relative z-10">
        @yield('content')
    </main>

    <footer class="relative z-10 mt-20 border-t border-white/10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 py-10 text-sm text-slate-400 sm:grid-cols-3">
            <div>
                <p class="text-base font-bold text-white">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</p>
                <p class="mt-2">{{ \App\Models\Setting::get('tagline') }}</p>
            </div>
            <div>
                <p class="font-semibold text-slate-200">Menu</p>
                <ul class="mt-2 space-y-1">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white">Semua Produk</a></li>
                    <li><a href="{{ route('admin.login') }}" class="hover:text-white">Admin</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-slate-200">Kontak</p>
                <ul class="mt-2 space-y-1">
                    <li>{{ \App\Models\Setting::get('contact_email') }}</li>
                    @if($ig = \App\Models\Setting::get('instagram_url'))
                        <li><a href="{{ $ig }}" target="_blank" rel="noopener" class="hover:text-white">Instagram</a></li>
                    @endif
                    @if($tt = \App\Models\Setting::get('tiktok_url'))
                        <li><a href="{{ $tt }}" target="_blank" rel="noopener" class="hover:text-white">TikTok</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. Semua hak dilindungi.
        </div>
    </footer>
</body>
</html>
