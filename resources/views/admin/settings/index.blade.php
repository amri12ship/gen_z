@extends('admin.layouts.app')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan')
@section('subheading', 'Atur identitas dan kontak website')

@section('content')
    @php
        $input = 'w-full rounded-xl bg-white/5 px-3 py-2.5 text-sm text-white placeholder-slate-500 outline-none ring-1 ring-white/10 transition focus:ring-brand-400';
        $label = 'mb-1 block text-xs font-medium text-slate-300';
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="glass rounded-2xl p-5">
            <h2 class="mb-4 font-bold text-white">Identitas Website</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Nama Website *</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $settings['tagline']) }}" class="{{ $input }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Deskripsi</label>
                    <textarea name="description" rows="3" class="{{ $input }}">{{ old('description', $settings['description']) }}</textarea>
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl p-5">
            <h2 class="mb-4 font-bold text-white">Kontak & Sosial Media</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">URL Instagram</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="{{ $input }}" placeholder="https://instagram.com/...">
                </div>
                <div>
                    <label class="{{ $label }}">URL TikTok</label>
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url']) }}" class="{{ $input }}" placeholder="https://tiktok.com/@...">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
    </form>
@endsection
