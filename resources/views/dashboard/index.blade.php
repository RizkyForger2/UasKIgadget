@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">
        <i class="fas fa-tachometer-alt text-blue-600"></i> Dashboard Admin
    </h1>
    <p class="text-gray-600">Selamat datang di panel admin KiGadGet</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Produk -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-semibold uppercase">Total Produk</p>
                <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-mobile-alt text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-blue-100">
            <i class="fas fa-box-open"></i> {{ $lowStockProducts }} produk stok rendah
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-semibold uppercase">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-green-100">
            <i class="fas fa-clock"></i> {{ $pendingOrders }} pesanan pending
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-semibold uppercase">Total Revenue</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-dollar-sign text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-purple-100">
            <i class="fas fa-check-circle"></i> Pesanan selesai
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-semibold uppercase">Total Kategori</p>
                <p class="text-3xl font-bold mt-2">{{ $totalCategories }}</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-tags text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-orange-100">
            <i class="fas fa-list"></i> Kategori produk
        </div>
    </div>
</div>

<!-- Charts & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-shopping-bag text-blue-600 mr-2"></i>
            Pesanan Terbaru
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 text-sm text-gray-600">Order ID</th>
                        <th class="text-left py-2 text-sm text-gray-600">Customer</th>
                        <th class="text-right py-2 text-sm text-gray-600">Total</th>
                        <th class="text-center py-2 text-sm text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 text-sm font-semibold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 text-sm">{{ $order->customer_name }}</td>
                            <td class="py-3 text-sm text-right font-semibold text-blue-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Belum ada pesanan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
            Produk Terlaris
        </h3>
        <div class="space-y-4">
            @forelse($topProducts as $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->brand }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">{{ $product->total_sold ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Terjual</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box text-3xl mb-2"></i>
                    <p>Belum ada penjualan</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Lihat Semua Produk <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Pending Reviews -->
@if($pendingReviews->count() > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center justify-between">
        <span><i class="fas fa-star text-yellow-500 mr-2"></i> Review Menunggu Persetujuan</span>
        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
            {{ $pendingReviews->count() }} pending
        </span>
    </h3>
    <div class="space-y-3">
        @foreach($pendingReviews as $review)
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <p class="font-semibold text-gray-800">{{ $review->customer_name }}</p>
                            <span class="text-gray-400">•</span>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $review->comment }}</p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-mobile-alt"></i> {{ $review->product->name }}
                        </p>
                    </div>
                    <form action="{{ route('reviews.approve', $review) }}" method="POST" class="ml-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm transition">
                            <i class="fas fa-check"></i> Setujui
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        <a href="{{ route('reviews.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
            Lihat Semua Review <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <a href="{{ route('products.create') }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-6 text-center transition shadow-md">
        <i class="fas fa-plus-circle text-3xl mb-2"></i>
        <p class="font-semibold">Tambah Produk</p>
    </a>
    <a href="{{ route('orders.create') }}" 
       class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-6 text-center transition shadow-md">
        <i class="fas fa-shopping-cart text-3xl mb-2"></i>
        <p class="font-semibold">Buat Pesanan</p>
    </a>
    <a href="{{ route('categories.create') }}" 
       class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-6 text-center transition shadow-md">
        <i class="fas fa-tag text-3xl mb-2"></i>
        <p class="font-semibold">Tambah Kategori</p>
    </a>
    <a href="{{ route('reviews.index') }}" 
       class="bg-orange-500 hover:bg-orange-600 text-white rounded-lg p-6 text-center transition shadow-md">
        <i class="fas fa-star text-3xl mb-2"></i>
        <p class="font-semibold">Kelola Review</p>
    </a>
</div>
@endsection