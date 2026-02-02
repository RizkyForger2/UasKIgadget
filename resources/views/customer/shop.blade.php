@extends('layouts.customer')

@section('title', 'Shop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-store text-blue-600"></i> Shop
    </h1>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('shop') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Produk</label>
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}"
                       placeholder="Cari nama atau brand..." 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if($search || $category)
                    <a href="{{ route('shop') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <a href="{{ route('shop.show', $product) }}">
                    <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-mobile-alt text-6xl text-gray-400"></i>
                        @endif
                        
                        @if($product->stock < 5 && $product->stock > 0)
                            <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                Stok Terbatas!
                            </span>
                        @elseif($product->stock == 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                Habis
                            </span>
                        @endif
                    </div>
                </a>
                
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                            {{ $product->brand }}
                        </span>
                        @if($product->category)
                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $product->name }}</h3>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($product->averageRating()) ? '' : ' opacity-30' }}"></i>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-600">({{ $product->approvedReviews->count() }})</span>
                    </div>

                    <p class="text-2xl font-bold text-blue-600 mb-3">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-box"></i> Stok: {{ $product->stock }}
                    </p>

                    <a href="{{ route('shop.show', $product) }}" 
                       class="block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-center transition">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada produk yang ditemukan</p>
                <a href="{{ route('shop') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                    Lihat Semua Produk
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection