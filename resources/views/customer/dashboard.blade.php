@extends('layouts.customer')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-tachometer-alt text-blue-600"></i> Dashboard Saya
        </h1>
        <p class="text-gray-600">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase">Total Pesanan</p>
                    <p class="text-3xl font-bold mt-2">{{ $recentOrders->count() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-shopping-bag text-3xl"></i>
                </div>
            </div>
        </div>

        <a href="{{ route('shop') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold uppercase">Belanja Sekarang</p>
                    <p class="text-lg mt-2">Lihat Produk</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-store text-3xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('customer.orders') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-semibold uppercase">Pesanan Saya</p>
                    <p class="text-lg mt-2">Lihat Semua</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-list text-3xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-blue-600"></i> Pesanan Terakhir
        </h2>

@if($recentOrders->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-blue-600">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($order->payment_status === 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock"></i> Belum Bayar
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle"></i> Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
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
                        <td class="px-4 py-3 text-center">
                            @if($order->payment_status === 'pending')
                                <a href="{{ route('customer.payment', $order) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm">
                                    <i class="fas fa-qrcode"></i> Bayar
                                </a>
                            @else
                                <a href="{{ route('customer.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
            <div class="mt-4 text-center">
                <a href="{{ route('customer.orders') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">Anda belum memiliki pesanan</p>
                <a href="{{ route('shop') }}" 
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-store"></i> Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
@endsection