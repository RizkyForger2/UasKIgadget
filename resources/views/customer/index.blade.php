@extends('layouts.customer')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="container mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-5xl font-bold mb-4">Selamat Datang di KiGadGet</h1>
                <p class="text-xl mb-8">Temukan handphone impian Anda dengan harga terbaik dan kualitas original.</p>
                <a href="{{ route('shop') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-50 transition inline-block">
                    <i class="fas fa-store mr-2"></i> Belanja Sekarang
                </a>
            </div>
            <div class="text-center">
                <i class="fas fa-mobile-alt text-9xl opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Kategori Produk</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('shop', ['category' => $category->id]) }}" 
               class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                <i class="fas {{ $category->icon ?? 'fa-tag' }} text-5xl text-blue-600 mb-4"></i>
                <h3 class="font-bold text-gray-800">{{ $category->name }}</h3>
                <p class="text-sm text-gray-600">{{ $category->products_count }} produk</p>
            </a>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">Belum ada kategori</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Featured Products -->
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Produk Terbaru</h2>
            <a href="{{ route('shop') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('shop.show', $product) }}">
                        <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-mobile-alt text-6xl text-gray-400"></i>
                            @endif
                        </div>
                    </a>
                    
                    <div class="p-4">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                            {{ $product->brand }}
                        </span>
                        <h3 class="font-bold text-lg text-gray-800 mt-2 mb-2">
                            <a href="{{ route('shop.show', $product) }}" class="hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
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

                        <a href="{{ route('shop.show', $product) }}" 
                           class="block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-center transition">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada produk</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
            <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-4xl text-blue-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">100% Original</h3>
            <p class="text-gray-600">Semua produk dijamin original dengan garansi resmi</p>
        </div>
        <div class="text-center">
            <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shipping-fast text-4xl text-green-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Pengiriman Cepat</h3>
            <p class="text-gray-600">Gratis ongkir untuk pembelian minimal tertentu</p>
        </div>
        <div class="text-center">
            <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-headset text-4xl text-purple-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Customer Support</h3>
            <p class="text-gray-600">Tim support siap membantu 24/7</p>
        </div>
    </div>
</div>
@endsection