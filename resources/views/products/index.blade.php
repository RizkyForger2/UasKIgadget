@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">
        <i class="fas fa-mobile-alt text-blue-600"></i> Daftar Produk HP
    </h1>
    <p class="text-gray-600">Kelola semua produk handphone di toko Anda</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <i class="fas fa-mobile-alt text-6xl text-gray-400"></i>
                @endif
            </div>
            
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-800">{{ $product->name }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                        {{ $product->brand }}
                    </span>
                </div>
                
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                    {{ Str::limit($product->description, 80) }}
                </p>
                
                <div class="space-y-1 text-xs text-gray-600 mb-3">
                    @if($product->processor)
                        <p><i class="fas fa-microchip w-4"></i> {{ $product->processor }}</p>
                    @endif
                    @if($product->ram)
                        <p><i class="fas fa-memory w-4"></i> RAM: {{ $product->ram }}</p>
                    @endif
                    @if($product->storage)
                        <p><i class="fas fa-hdd w-4"></i> Storage: {{ $product->storage }}</p>
                    @endif
                </div>
                
                <div class="border-t pt-3 mt-3">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas fa-box"></i> Stok: {{ $product->stock }}
                        </span>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('products.show', $product) }}" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded text-center text-sm transition">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('products.edit', $product) }}" 
                           class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-center text-sm transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada produk. Silakan tambahkan produk baru.</p>
            <a href="{{ route('products.create') }}" 
               class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                <i class="fas fa-plus-circle"></i> Tambah Produk Pertama
            </a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $products->links() }}
</div>
@endsection