@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <i class="fas fa-edit text-blue-600"></i> Edit Kategori
        </h2>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Icon Font Awesome</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Preview: <i class="fas {{ $category->icon ?? 'fa-tag' }} text-2xl text-blue-600 ml-2"></i>
                    </p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save"></i> Update Kategori
                </button>
                <a href="{{ route('categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection