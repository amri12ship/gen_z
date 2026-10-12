<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - {{ \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <canvas id="bg-3d"></canvas>
    <div class="bg-overlay"></div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4">
        <div class="glass-strong w-full max-w-md rounded-3xl p-8">
            <div class="flex items-center gap-3">
                <span class="float grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 via-fuchsia-500 to-cyan-400 text-lg font-black text-white shadow-lg">G</span>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-white">Admin Panel</h1>
                    <p class="text-xs text-slate-400">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mt-6 rounded-xl border-l-4 border-rose-400 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-xl bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none ring-1 ring-white/10 transition focus:ring-brand-400">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-300">Password</label>
                    <input type="password" name="password" required
                           class="w-full rounded-xl bg-white/5 px-4 py-3 text-sm text-white outline-none ring-1 ring-white/10 transition focus:ring-brand-400">
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-300">
                    <input type="checkbox" name="remember" value="1" class="rounded border-white/20 bg-white/5">
                    Ingat saya
                </label>

                <button type="submit" class="btn btn-primary w-full text-base">Masuk</button>
            </form>

            <a href="{{ route('home') }}" class="mt-6 block text-center text-xs text-slate-400 hover:text-white">&larr; Kembali ke situs</a>
        </div>
    </div>
</body>
</html>
