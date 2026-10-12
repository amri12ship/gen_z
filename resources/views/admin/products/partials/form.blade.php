@php
    $isEdit = $product->exists;
    $input = 'w-full rounded-xl bg-white/5 px-3 py-2.5 text-sm text-white placeholder-slate-500 outline-none ring-1 ring-white/10 transition focus:ring-brand-400';
    $label = 'mb-1 block text-xs font-medium text-slate-300';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="glass rounded-2xl p-5">
        <h2 class="mb-4 font-bold text-white">Informasi Produk</h2>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="{{ $label }}">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="{{ $input }}" placeholder="Contoh: TWS Bass Boost">
            </div>

            <div>
                <label class="{{ $label }}">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="{{ $input }}" placeholder="otomatis dari nama">
            </div>

            <div>
                <label class="{{ $label }}">Kategori</label>
                <select name="category_id" class="{{ $input }} [&>option]:bg-slate-900">
                    <option value="">- Tanpa kategori -</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="{{ $label }}">Harga (Rp)</label>
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="{{ $input }}" placeholder="0">
            </div>

            <div>
                <label class="{{ $label }}">Rating (0-5)</label>
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $product->rating) }}" class="{{ $input }}" placeholder="5">
            </div>

            <div>
                <label class="{{ $label }}">Jumlah Terjual</label>
                <input type="number" name="sold_count" min="0" value="{{ old('sold_count', $product->sold_count) }}" class="{{ $input }}" placeholder="0">
            </div>

            <div>
                <label class="{{ $label }}">Status</label>
                <select name="status" class="{{ $input }} [&>option]:bg-slate-900">
                    <option value="active" @selected(old('status', $product->status) === 'active')>Aktif</option>
                    <option value="inactive" @selected(old('status', $product->status) === 'inactive')>Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Link Affiliate *</label>
                <input type="url" name="affiliate_url" value="{{ old('affiliate_url', $product->affiliate_url) }}" required class="{{ $input }}" placeholder="https://shopee.co.id/...">
                <p class="mt-1 text-xs text-slate-500">Link tujuan saat pengunjung menekan tombol "Beli Sekarang".</p>
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Deskripsi</label>
                <textarea name="description" rows="4" class="{{ $input }}" placeholder="Deskripsi produk...">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="sm:col-span-2 flex flex-wrap gap-5">
                <label class="flex items-center gap-2 text-sm text-slate-200">
                    <input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $product->is_trending)) class="rounded border-white/20 bg-white/5"> Trending
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-200">
                    <input type="checkbox" name="is_viral" value="1" @checked(old('is_viral', $product->is_viral)) class="rounded border-white/20 bg-white/5"> Viral
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-200">
                    <input type="checkbox" name="is_recommended" value="1" @checked(old('is_recommended', $product->is_recommended)) class="rounded border-white/20 bg-white/5"> Rekomendasi
                </label>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-5">
        <h2 class="mb-1 font-bold text-white">Foto Produk</h2>
        <p class="mb-4 text-xs text-slate-400">Format JPG, PNG, atau WEBP. Maksimal {{ \App\Models\Product::MAX_IMAGES }} foto, tiap foto maks 4MB.</p>

        @if($isEdit && $product->images->isNotEmpty())
            <div class="mb-5">
                <p class="mb-2 text-xs font-medium text-slate-300">Foto saat ini (centang untuk hapus):</p>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach($product->images as $image)
                        <label class="group relative block cursor-pointer overflow-hidden rounded-xl ring-1 ring-white/10">
                            <img src="{{ $image->url() }}" alt="" class="aspect-square w-full object-cover opacity-90 transition group-hover:opacity-100">
                            <span class="absolute left-2 top-2 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="rounded border-white/30 bg-white/10">
                                Hapus
                            </span>
                            <span class="absolute bottom-2 right-2 rounded bg-black/60 px-2 py-0.5 text-[10px] text-white">#{{ $image->sort_order }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <input type="file" name="images[]" accept="image/*" multiple
               onchange="previewImages(this)"
               class="block w-full cursor-pointer rounded-xl bg-white/5 px-3 py-8 text-sm text-slate-300 outline-none ring-1 ring-dashed ring-white/20 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600">

        <div id="image-preview" class="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-4"></div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>

<script>
    function previewImages(input) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        Array.from(input.files).forEach((file) => {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'overflow-hidden rounded-xl ring-1 ring-white/10';
            div.innerHTML = '<img src="' + url + '" class="aspect-square w-full object-cover">';
            preview.appendChild(div);
        });
    }
</script>
