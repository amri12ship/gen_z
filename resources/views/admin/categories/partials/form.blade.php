@php
    $isEdit = $category->exists;
    $input = 'w-full rounded-xl bg-white/5 px-3 py-2.5 text-sm text-white placeholder-slate-500 outline-none ring-1 ring-white/10 transition focus:ring-brand-400';
    $label = 'mb-1 block text-xs font-medium text-slate-300';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="glass rounded-2xl p-5">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $label }}">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="{{ $input }}" placeholder="otomatis dari nama">
            </div>
            <div class="sm:col-span-2">
                <label class="{{ $label }}">Deskripsi</label>
                <textarea name="description" rows="3" class="{{ $input }}">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Foto Kategori</label>
                @if($isEdit && $category->imageUrl())
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $category->imageUrl() }}" alt="" class="h-20 w-20 rounded-xl object-cover ring-1 ring-white/10">
                        <span class="text-xs text-slate-400">Foto saat ini. Unggah foto baru untuk mengganti.</span>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="block w-full cursor-pointer rounded-xl bg-white/5 px-3 py-4 text-sm text-slate-300 outline-none ring-1 ring-dashed ring-white/20 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600">
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kategori' }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
