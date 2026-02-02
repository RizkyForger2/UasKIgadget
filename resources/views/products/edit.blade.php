@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <i class="fas fa-edit text-blue-600"></i> Edit Produk
        </h2>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Produk -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Brand *</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('brand') border-red-500 @enderror" 
                           required>
                    @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Kategori (TAMBAHAN BARU) -->
                 <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <select name="category_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
                    </option>
                @endforeach
                    </select>
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('price') border-red-500 @enderror" 
                           min="0" required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('stock') border-red-500 @enderror" 
                           min="0" required>
                    @error('stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Processor -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Processor</label>
                    <input type="text" name="processor" value="{{ old('processor', $product->processor) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- RAM -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">RAM</label>
                    <input type="text" name="ram" value="{{ old('ram', $product->ram) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Storage -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Storage</label>
                    <input type="text" name="storage" value="{{ old('storage', $product->storage) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Camera -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kamera</label>
                    <input type="text" name="camera" value="{{ old('camera', $product->camera) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Battery -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Baterai</label>
                    <input type="text" name="battery" value="{{ old('battery', $product->battery) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Gambar -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Produk</label>
                    
                    @if($product->image)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="max-w-xs rounded-lg shadow">
                        </div>
                    @endif
                    
                    <input type="file" name="image" accept="image/*" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('image') border-red-500 @enderror"
                           onchange="previewImage(event)">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar</p>
                    
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview Gambar Baru:</p>
                        <img id="preview" class="max-w-xs rounded-lg shadow">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi *</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror" 
                              required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save"></i> Update Produk
                </button>
                <a href="{{ route('products.show', $product) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection